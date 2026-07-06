<?php

namespace App\Services;

use App\Exceptions\GoogleCalendarException;
use App\Models\Booking;
use App\Models\BookingConfig;
use App\Models\GoogleCalendarToken;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventReminders;
use Google\Service\Calendar\FreeBusyRequest;
use Google\Service\Calendar\FreeBusyRequestItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    private Client $client;

    public function __construct()
    {
        $this->client = $this->buildClient();
    }

    // ─── OAuth ───────────────────────────────────────────────────────────────

    /**
     * Generate the Google OAuth consent URL.
     */
    public function createAuthUrl(): string
    {
        $this->client->setState('admin:' . Auth::id());

        return $this->client->createAuthUrl();
    }

    /**
     * Handle the OAuth callback: exchange code for tokens and persist them.
     */
    public function handleCallback(string $code): GoogleCalendarToken
    {
        $tokenData = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($tokenData['error'])) {
            throw GoogleCalendarException::apiError($tokenData['error']);
        }

        // Get the user's Google email via the OAuth2 userinfo endpoint
        $this->client->setAccessToken($tokenData['access_token']);
        $oauth2 = new \Google\Service\Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();

        $userId = Auth::id();

        // Upsert: replace any existing token for this user
        $token = GoogleCalendarToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'access_token'     => $tokenData['access_token'],
                'refresh_token'    => $tokenData['refresh_token'] ?? null,
                'token_expires_at' => time() + ($tokenData['expires_in'] ?? 3600),
                'calendar_id'      => config('google-calendar.default_calendar_id', 'primary'),
                'google_email'     => $userInfo->email ?? null,
                'is_active'        => true,
                'connected_at'     => now(),
                'last_error'       => null,
            ]
        );

        return $token;
    }

    /**
     * Revoke the token and delete the record.
     */
    public function disconnect(GoogleCalendarToken $token): void
    {
        try {
            $this->client->setAccessToken([
                'access_token'  => $token->access_token,
                'refresh_token' => $token->refresh_token,
            ]);
            $this->client->revokeToken();
        } catch (\Throwable $e) {
            Log::warning("GoogleCalendar: Failed to revoke token during disconnect: {$e->getMessage()}");
        }
    }

    /**
     * Get an authenticated Google\Client, auto-refreshing the token if needed.
     */
    public function getAuthenticatedClient(?GoogleCalendarToken $token = null): Client
    {
        $token = $token ?? GoogleCalendarToken::where('is_active', true)->first();

        if (!$token) {
            throw GoogleCalendarException::notConnected();
        }

        if ($token->needsRefresh()) {
            $this->refreshToken($token);
        }

        $this->client->setAccessToken([
            'access_token'  => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in'    => $token->token_expires_at - time(),
            'created'       => time() - 3000, // simulate created 50 min ago
        ]);

        return $this->client;
    }

    /**
     * Refresh the access token using the refresh token.
     */
    public function refreshToken(GoogleCalendarToken $token): void
    {
        try {
            $this->client->fetchAccessTokenWithRefreshToken($token->refresh_token);
            $newToken = $this->client->getAccessToken();

            $token->update([
                'access_token'     => $newToken['access_token'],
                'token_expires_at' => time() + ($newToken['expires_in'] ?? 3600),
                'last_error'       => null,
            ]);
        } catch (\Throwable $e) {
            $token->update([
                'is_active'  => false,
                'last_error' => 'Token refresh failed: ' . $e->getMessage(),
            ]);

            throw GoogleCalendarException::tokenExpired(
                'Google Calendar token refresh failed. Please reconnect your Google account.'
            );
        }
    }

    // ─── Calendar Operations ─────────────────────────────────────────────────

    /**
     * Create a Google Calendar event for a confirmed booking.
     *
     * @return string The Google event ID.
     */
    public function createEvent(Booking $booking, string $calendarId): string
    {
        $client = $this->getAuthenticatedClient();
        $service = new Calendar($client);
        $timezone = $this->getAppTimezone();

        $scheduledAt = Carbon::parse($booking->scheduled_at ?? $booking->preferred_date, $timezone);
        $slotDuration = BookingConfig::settings()->slot_duration;
        $endTime = $scheduledAt->copy()->addMinutes($slotDuration);

        $formatLabels = [
            'intake'   => 'Introduction Call',
            'standard' => 'Standard Session',
            'emdr'     => 'EMDR Session',
            'initial'  => 'Initial Session',
        ];

        $event = new Event($service);
        $event->setSummary('Session: ' . $booking->full_name);
        $event->setDescription(implode("\n", array_filter([
            'Client: ' . $booking->full_name,
            'Email: ' . $booking->email,
            'Phone: ' . ($booking->phone ?? 'Not provided'),
            'Format: ' . ($formatLabels[$booking->session_format] ?? ucfirst($booking->session_format ?? '')),
            'Type: ' . ucfirst($booking->session_type ?? 'N/A'),
            'Language: ' . ($booking->preferred_language ?? 'N/A'),
            $booking->reason ? 'Reason: ' . $booking->reason : null,
            $booking->meeting_link ? 'Meeting Link: ' . $booking->meeting_link : null,
        ])));

        if ($booking->meeting_link) {
            $event->setLocation($booking->meeting_link);
        }

        $start = new EventDateTime();
        $start->setDateTime($scheduledAt->format('Y-m-d\TH:i:s'));
        $start->setTimeZone($timezone);
        $event->setStart($start);

        $end = new EventDateTime();
        $end->setDateTime($endTime->format('Y-m-d\TH:i:s'));
        $end->setTimeZone($timezone);
        $event->setEnd($end);

        // Set attendees (calendar invitations)
        $attendees = [];

        // Client attendee
        if ($booking->email) {
            $clientAttendee = new EventAttendee();
            $clientAttendee->setEmail($booking->email);
            $clientAttendee->setDisplayName($booking->full_name);
            $attendees[] = $clientAttendee;
        }

        // Therapist (organizer) attendee
        $token = GoogleCalendarToken::where('is_active', true)->first();
        if ($token && $token->google_email) {
            $therapistAttendee = new EventAttendee();
            $therapistAttendee->setEmail($token->google_email);
            $therapistAttendee->setDisplayName('Therapist');
            $attendees[] = $therapistAttendee;
        }

        if (!empty($attendees)) {
            $event->setAttendees($attendees);
        }

        // Set reminders
        $reminder = new Calendar\EventReminder();
        $reminder->setMethod('popup');
        $reminder->setMinutes(30);
        $reminders = new Calendar\EventReminders();
        $reminders->setUseDefault(false);
        $reminders->setOverrides([$reminder]);
        $event->setReminders($reminders);

        $createdEvent = $service->events->insert($calendarId, $event, ['sendUpdates' => 'all']);

        return $createdEvent->getId();
    }

    /**
     * Update an existing Google Calendar event.
     */
    public function updateEvent(string $eventId, Booking $booking, string $calendarId): void
    {
        $client = $this->getAuthenticatedClient();
        $service = new Calendar($client);
        $timezone = $this->getAppTimezone();

        $existingEvent = $service->events->get($calendarId, $eventId);

        $scheduledAt = Carbon::parse($booking->scheduled_at ?? $booking->preferred_date, $timezone);
        $slotDuration = BookingConfig::settings()->slot_duration;
        $endTime = $scheduledAt->copy()->addMinutes($slotDuration);

        $formatLabels = [
            'intake'   => 'Introduction Call',
            'standard' => 'Standard Session',
            'emdr'     => 'EMDR Session',
            'initial'  => 'Initial Session',
        ];

        $existingEvent->setSummary('Session: ' . $booking->full_name);
        $existingEvent->setDescription(implode("\n", array_filter([
            'Client: ' . $booking->full_name,
            'Email: ' . $booking->email,
            'Phone: ' . ($booking->phone ?? 'Not provided'),
            'Format: ' . ($formatLabels[$booking->session_format] ?? ucfirst($booking->session_format ?? '')),
            'Type: ' . ucfirst($booking->session_type ?? 'N/A'),
            'Language: ' . ($booking->preferred_language ?? 'N/A'),
            $booking->reason ? 'Reason: ' . $booking->reason : null,
            $booking->meeting_link ? 'Meeting Link: ' . $booking->meeting_link : null,
        ])));

        if ($booking->meeting_link) {
            $existingEvent->setLocation($booking->meeting_link);
        }

        $start = new EventDateTime();
        $start->setDateTime($scheduledAt->format('Y-m-d\TH:i:s'));
        $start->setTimeZone($timezone);
        $existingEvent->setStart($start);

        $end = new EventDateTime();
        $end->setDateTime($endTime->format('Y-m-d\TH:i:s'));
        $end->setTimeZone($timezone);
        $existingEvent->setEnd($end);

        // Update attendees (calendar invitations)
        $attendees = [];

        if ($booking->email) {
            $clientAttendee = new EventAttendee();
            $clientAttendee->setEmail($booking->email);
            $clientAttendee->setDisplayName($booking->full_name);
            $attendees[] = $clientAttendee;
        }

        $token = GoogleCalendarToken::where('is_active', true)->first();
        if ($token && $token->google_email) {
            $therapistAttendee = new EventAttendee();
            $therapistAttendee->setEmail($token->google_email);
            $therapistAttendee->setDisplayName('Therapist');
            $attendees[] = $therapistAttendee;
        }

        if (!empty($attendees)) {
            $existingEvent->setAttendees($attendees);
        }

        $service->events->update($calendarId, $eventId, $existingEvent, ['sendUpdates' => 'all']);
    }

    /**
     * Delete a Google Calendar event.
     */
    public function deleteEvent(string $eventId, string $calendarId): void
    {
        $client = $this->getAuthenticatedClient();
        $service = new Calendar($client);

        $service->events->delete($calendarId, $eventId, ['sendUpdates' => 'all']);
    }

    // ─── Availability ────────────────────────────────────────────────────────

    /**
     * Get busy time slots from Google Calendar for a date range.
     *
     * @return array Array of ['start' => 'H:i', 'end' => 'H:i'] entries.
     */
    public function getBusySlots(Carbon $start, Carbon $end, string $calendarId): array
    {
        return $this->getBusySlotsForCalendars($start, $end, [$calendarId]);
    }

    /**
     * Get busy time slots from multiple Google Calendars in a single FreeBusy request.
     * Returns merged busy periods across all calendars.
     *
     * @param array $calendarIds Array of calendar IDs to check.
     * @return array Array of ['start' => 'H:i', 'end' => 'H:i', 'start_dt' => Carbon, 'end_dt' => Carbon].
     */
    public function getBusySlotsForCalendars(Carbon $start, Carbon $end, array $calendarIds): array
    {
        if (empty($calendarIds)) {
            return [];
        }

        $client = $this->getAuthenticatedClient();
        $service = new Calendar($client);

        // Build FreeBusy request items for all calendars
        $items = [];
        foreach ($calendarIds as $calId) {
            $item = new FreeBusyRequestItem();
            $item->setId($calId);
            $items[] = $item;
        }

        $request = new FreeBusyRequest();
        $request->setTimeMin($start->toIso8601String());
        $request->setTimeMax($end->toIso8601String());
        $request->setTimeZone($this->getAppTimezone());
        $request->setItems($items);

        $response = $service->freebusy->query($request);
        $busySlots = [];

        // Merge busy periods from all calendars
        $calendars = $response->getCalendars();
        foreach ($calendarIds as $calId) {
            if (isset($calendars[$calId])) {
                foreach ($calendars[$calId]->getBusy() as $busy) {
                    $busyStart = Carbon::parse($busy->getStart());
                    $busyEnd = Carbon::parse($busy->getEnd());
                    $busySlots[] = [
                        'start' => $busyStart->format('H:i'),
                        'end'   => $busyEnd->format('H:i'),
                        'start_dt' => $busyStart,
                        'end_dt'   => $busyEnd,
                    ];
                }
            }
        }

        // Sort by start time for consistent ordering
        usort($busySlots, fn($a, $b) => $a['start_dt'] <=> $b['start_dt']);

        return $busySlots;
    }

    /**
     * Check if a specific time slot is busy on Google Calendar.
     */
    public function isSlotBusy(Carbon $slotStart, Carbon $slotEnd, string $calendarId): bool
    {
        $busySlots = $this->getBusySlots($slotStart->copy()->startOfDay(), $slotEnd->copy()->endOfDay(), $calendarId);

        foreach ($busySlots as $busy) {
            $busyStart = $busy['start_dt'];
            $busyEnd = $busy['end_dt'];

            // Overlap check: slot overlaps with busy period
            if ($slotStart < $busyEnd && $slotEnd > $busyStart) {
                return true;
            }
        }

        return false;
    }

    /**
     * List available calendars for the authenticated user.
     *
     * @return array Array of ['id' => string, 'summary' => string].
     */
    public function listCalendars(): array
    {
        $client = $this->getAuthenticatedClient();
        $service = new Calendar($client);

        $calendarList = $service->calendarList->listCalendarList();
        $calendars = [];

        foreach ($calendarList->getItems() as $cal) {
            $calendars[] = [
                'id'      => $cal->getId(),
                'summary' => $cal->getSummary(),
            ];
        }

        return $calendars;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Get a Google Calendar config value from SiteSetting, falling back to env/config.
     */
    private function getGoogleConfig(string $key): ?string
    {
        $settingKey = match ($key) {
            'client_id'     => 'google_client_id',
            'client_secret' => 'google_client_secret',
            'redirect_uri'  => 'google_calendar_redirect_uri',
            'calendar_id'   => 'google_calendar_id',
            default         => null,
        };

        if ($settingKey) {
            $setting = SiteSetting::where('key', $settingKey)->first();
            if ($setting && $setting->getRawOriginal('value')) {
                return $setting->getRawOriginal('value');
            }
        }

        return config("google-calendar.{$key}");
    }

    /**
     * Check if Google OAuth credentials are configured.
     */
    public static function isConfigured(): bool
    {
        $service = new static();
        return !empty($service->getGoogleConfig('client_id'))
            && !empty($service->getGoogleConfig('client_secret'));
    }

    private function buildClient(): Client
    {
        $client = new Client();
        $client->setApplicationName(config('app.name', 'Therapist Lysander'));
        $client->setClientId($this->getGoogleConfig('client_id'));
        $client->setClientSecret($this->getGoogleConfig('client_secret'));
        $client->setRedirectUri($this->getGoogleConfig('redirect_uri'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes([
            Calendar::CALENDAR,
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]);

        return $client;
    }

    /**
     * Get the application timezone from SiteSettings.
     */
    private function getAppTimezone(): string
    {
        $tzSetting = SiteSetting::where('key', 'timezone')->first();

        return $tzSetting?->value ?: config('app.timezone', 'Europe/Amsterdam');
    }
}
