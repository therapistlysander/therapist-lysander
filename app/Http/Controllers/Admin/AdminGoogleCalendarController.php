<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\WarmCalendarCacheJob;
use App\Models\Booking;
use App\Models\GoogleCalendarToken;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminGoogleCalendarController extends Controller
{
    /**
     * Display the Google Calendar settings page.
     */
    public function index()
    {
        $token = GoogleCalendarToken::where('user_id', auth()->id())->first();
        $calendars = [];
        $connectionError = null;
        $syncedBookings = collect();
        $stats = [
            'total_synced' => 0,
            'upcoming' => 0,
            'this_week' => 0,
        ];

        if ($token && $token->is_active) {
            try {
                $calendar = app(GoogleCalendarService::class);
                $calendars = $calendar->listCalendars();
            } catch (\Throwable $e) {
                $connectionError = $e->getMessage();
                Log::warning("GoogleCalendar: Failed to list calendars: {$e->getMessage()}");
            }

            // Get bookings synced to Google Calendar
            $syncedBookings = Booking::whereNotNull('google_event_id')
                ->where('status', 'confirmed')
                ->orderBy('scheduled_at', 'desc')
                ->limit(10)
                ->get();

            // Calculate stats
            $stats['total_synced'] = Booking::whereNotNull('google_event_id')->count();
            $stats['upcoming'] = Booking::whereNotNull('google_event_id')
                ->where('status', 'confirmed')
                ->where('scheduled_at', '>', now())
                ->count();
            $stats['this_week'] = Booking::whereNotNull('google_event_id')
                ->where('status', 'confirmed')
                ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
        }

        return view('admin.pages.google-calendar.index', compact('token', 'calendars', 'connectionError', 'syncedBookings', 'stats'));
    }

    /**
     * Redirect to Google OAuth consent screen.
     */
    public function connect()
    {
        if (!GoogleCalendarService::isConfigured()) {
            return back()->with('error', 'Google OAuth credentials are not configured. Please enter your Client ID and Client Secret below.');
        }

        try {
            $calendar = app(GoogleCalendarService::class);
            $authUrl = $calendar->createAuthUrl();

            return redirect($authUrl);
        } catch (\Throwable $e) {
            Log::error("GoogleCalendar: Failed to generate auth URL: {$e->getMessage()}");
            return back()->with('error', 'Failed to initiate Google OAuth. Please try again.');
        }
    }

    /**
     * Save Google OAuth credentials from the admin form.
     */
    public function saveCredentials(Request $request)
    {
        $request->validate([
            'google_client_id'     => 'required|string|max:255',
            'google_client_secret' => 'required|string|max:255',
            'google_calendar_redirect_uri' => 'nullable|url|max:255',
            'google_calendar_id'   => 'nullable|string|max:255',
        ]);

        $fields = [
            'google_client_id'             => ['Google Client ID', 'text'],
            'google_client_secret'         => ['Google Client Secret', 'text'],
            'google_calendar_redirect_uri' => ['Google Calendar Redirect URI', 'text'],
            'google_calendar_id'           => ['Google Calendar ID', 'text'],
        ];

        foreach ($fields as $key => [$label, $type]) {
            $value = $request->input($key, '');
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'google_calendar', 'type' => $type, 'label' => $label]
            );
        }

        // Clear config cache so new values take effect
        \Artisan::call('config:clear');

        return back()->with('success', 'Google OAuth credentials saved.');
    }

    /**
     * Handle the OAuth callback from Google.
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');
        $error = $request->query('error');

        if ($error) {
            return redirect()->route('admin.google-calendar.index')
                ->with('error', 'Google authorization was denied.');
        }

        if (!$code) {
            return redirect()->route('admin.google-calendar.index')
                ->with('error', 'No authorization code received from Google.');
        }

        try {
            $calendar = app(GoogleCalendarService::class);
            $calendar->handleCallback($code);

            // Warm the cache after connecting
            WarmCalendarCacheJob::dispatch();

            return redirect()->route('admin.google-calendar.index')
                ->with('success', 'Google Calendar connected successfully!');
        } catch (\Throwable $e) {
            Log::error("GoogleCalendar: OAuth callback failed: {$e->getMessage()}");
            return redirect()->route('admin.google-calendar.index')
                ->with('error', 'Failed to complete Google authentication: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google Calendar.
     */
    public function disconnect()
    {
        $token = GoogleCalendarToken::where('user_id', auth()->id())->first();

        if ($token) {
            try {
                $calendar = app(GoogleCalendarService::class);
                $calendar->disconnect($token);
            } catch (\Throwable $e) {
                Log::warning("GoogleCalendar: Failed to revoke token during disconnect: {$e->getMessage()}");
            }

            $token->delete();

            // Clear any cached data
            Cache::forget('google_calendar_busy_slots');
        }

        return redirect()->route('admin.google-calendar.index')
            ->with('success', 'Google Calendar disconnected.');
    }

    /**
     * Update calendar settings (selected calendar).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'calendar_id' => 'required|string|max:255',
        ]);

        $token = GoogleCalendarToken::where('user_id', auth()->id())->first();

        if ($token) {
            $token->update(['calendar_id' => $request->calendar_id]);

            // Clear cached busy slots when calendar changes
            Cache::forget('google_calendar_busy_slots');

            // Re-warm cache with new calendar
            WarmCalendarCacheJob::dispatch();
        }

        return back()->with('success', 'Calendar settings updated.');
    }

    /**
     * Test the Google Calendar connection.
     */
    public function testSync()
    {
        $token = GoogleCalendarToken::where('user_id', auth()->id())->first();

        if (!$token) {
            return back()->with('error', 'Google Calendar is not connected.');
        }

        try {
            $calendar = app(GoogleCalendarService::class);
            $calendars = $calendar->listCalendars();

            // Refresh token if needed
            if ($token->needsRefresh()) {
                $calendar->refreshToken($token);
            }

            return back()->with('success', 'Connection is working. Found ' . count($calendars) . ' calendar(s).');
        } catch (\Throwable $e) {
            Log::error("GoogleCalendar: Test sync failed: {$e->getMessage()}");
            return back()->with('error', 'Connection test failed: ' . $e->getMessage());
        }
    }
}
