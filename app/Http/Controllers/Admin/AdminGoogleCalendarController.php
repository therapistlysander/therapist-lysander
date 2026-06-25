<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\WarmCalendarCacheJob;
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

        if ($token && $token->is_active) {
            try {
                $calendar = app(GoogleCalendarService::class);
                $calendars = $calendar->listCalendars();
            } catch (\Throwable $e) {
                $connectionError = $e->getMessage();
                Log::warning("GoogleCalendar: Failed to list calendars: {$e->getMessage()}");
            }
        }

        return view('admin.pages.google-calendar.index', compact('token', 'calendars', 'connectionError'));
    }

    /**
     * Redirect to Google OAuth consent screen.
     */
    public function connect()
    {
        if (!config('google-calendar.client_id') || !config('google-calendar.client_secret')) {
            return back()->with('error', 'Google OAuth credentials are not configured. Please set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in your environment.');
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
