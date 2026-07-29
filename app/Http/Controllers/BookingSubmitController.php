<?php

namespace App\Http\Controllers;

use App\Jobs\SyncBookingToCalendarJob;
use App\Models\Booking;
use App\Models\BookingConfig;
use App\Models\GoogleCalendarToken;
use App\Models\PreIntakeResponse;
use App\Services\GoogleCalendarService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingSubmitController extends Controller
{
    /**
     * POST /booking
     *
     * Accepts the full booking form data from the frontend multi-step form.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'format'  => 'required|in:intake,standard,emdr,initial',
            'type'    => 'required|in:online,in-person',
            'date'    => 'required|date',
            'time'    => 'required|date_format:H:i',
        ]);

        // Split name into first/last
        $nameParts = explode(' ', trim($request->input('name')), 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? '';

        // Build preferred_date from date + time
        $preferredDate = $request->input('date') . ' ' . $request->input('time') . ':00';

        // Session length + configurable buffer between appointments.
        $config = BookingConfig::settings();
        $slotDuration = (int) $config->slot_duration;
        $buffer = (int) ($config->buffer_minutes ?? 0);

        $slotStart = Carbon::parse($preferredDate);
        $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

        // Double-booking prevention: reject if the requested session (plus the
        // buffer on either side) overlaps an existing booking on the same day.
        $existingBooking = Booking::whereDate('preferred_date', $slotStart->toDateString())
            ->whereIn('status', ['pending', 'confirmed', 'scheduled'])
            ->get()
            ->first(function ($booking) use ($slotStart, $slotEnd, $slotDuration, $buffer) {
                $busyStart = Carbon::parse($booking->preferred_date);
                $busyEnd = $busyStart->copy()->addMinutes($slotDuration);

                return $slotStart < $busyEnd->copy()->addMinutes($buffer)
                    && $slotEnd > $busyStart->copy()->subMinutes($buffer);
            });

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is no longer available. Please choose another time.',
            ], 409);
        }

        // Check Google Calendar for conflicts (fail open)
        $googleConflict = $this->checkGoogleCalendarConflict($preferredDate);
        if ($googleConflict) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is no longer available. Please choose another time.',
            ], 409);
        }

        // Create the booking. Wrap in try/catch so any persistence failure is
        // logged and surfaced instead of silently lost (Issue #1, #9).
        try {
            $booking = Booking::create([
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'email'          => $request->input('email'),
                'session_type'   => $request->input('type'),
                'session_format' => $request->input('format'),
                'preferred_date' => $preferredDate,
                // The frontend multi-step form sends the "reason for seeking
                // therapy" as `pi_brings`; `notes` is kept for backwards
                // compatibility. Persist whichever value is present (Issue #3).
                'reason'         => $request->input('notes') ?: $request->input('pi_brings'),
                'source'         => 'website',
                'status'         => 'pending',
                'client_timezone' => $request->input('client_timezone'),
                'preferred_language' => $request->input('preferred_language'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Booking: Failed to save booking request: ' . $e->getMessage(), [
                'email'     => $request->input('email'),
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'We could not process your request right now. Please try again in a moment.',
            ], 500);
        }

        // Create pre-intake response if any pre-intake data provided
        $supportAreas = $request->input('support_areas', []);
        $piBrings     = $request->input('pi_brings');
        $piTherapy    = $request->input('pi_therapy');
        $piComm       = $request->input('pi_comm');
        $piExpect     = $request->input('pi_expect');
        $piNotes      = $request->input('pi_notes');

        if ($piBrings || !empty($supportAreas) || $piTherapy || $piComm || $piExpect || $piNotes) {
            try {
                PreIntakeResponse::create([
                    'booking_id'           => $booking->id,
                    'first_name'           => $firstName,
                    'last_name'            => $lastName,
                    'email'                => $request->input('email'),
                    'presenting_issue'     => $piBrings ?: 'Not provided',
                    'brings_to_therapy'    => $piBrings,
                    'support_areas'        => !empty($supportAreas) ? $supportAreas : null,
                    'previous_therapy'     => $piTherapy,
                    'communication_style'  => $piComm,
                    'duration_expectation' => $piExpect,
                    'additional_notes'     => $piNotes,
                    'session_preference'   => $request->input('type'),
                    'status'               => 'pending',
                ]);
            } catch (\Throwable $e) {
                Log::error("Booking: Failed to save pre-intake response for booking #{$booking->id}: {$e->getMessage()}");
            }
        }

        // Notifications are queued; a failure here must never lose the booking (Issue #6, #9).
        try {
            $notifications = app(NotificationService::class);
            $notifications->sendBookingConfirmation($booking);
            $notifications->alertAdminNewBooking($booking);
        } catch (\Throwable $e) {
            Log::error("Booking: Failed to dispatch notifications for booking #{$booking->id}: {$e->getMessage()}");
        }

        // Create the Google Calendar event asynchronously. The job logs its own
        // errors and retries; a Calendar failure must never lose the booking (Issue #2, #6, #9).
        try {
            SyncBookingToCalendarJob::dispatch($booking, 'create');
        } catch (\Throwable $e) {
            Log::error("Booking: Failed to queue Google Calendar sync for booking #{$booking->id}: {$e->getMessage()}");
        }

        return response()->json([
            'success'    => true,
            'booking_id' => $booking->id,
            'message'    => 'Booking request submitted successfully.',
        ], 201);
    }

    /**
     * Check if the requested slot conflicts with Google Calendar.
     * Checks all configured availability calendars (multi-calendar support).
     * Fails open: returns false if Google is unavailable.
     */
    private function checkGoogleCalendarConflict(string $preferredDate): bool
    {
        $token = GoogleCalendarToken::where('is_active', true)->first();
        if (!$token) {
            return false;
        }

        try {
            $calendar = app(GoogleCalendarService::class);
            $config = BookingConfig::settings();
            $buffer = (int) ($config->buffer_minutes ?? 0);

            $slotStart = Carbon::parse($preferredDate);
            $slotEnd = $slotStart->copy()->addMinutes($config->slot_duration);

            // Check across all availability calendars (Therapist + Personal, etc.)
            $calendarIds = $token->getAvailabilityCalendarIds();
            $busySlots = $calendar->getBusySlotsForCalendars(
                $slotStart->copy()->startOfDay(),
                $slotEnd->copy()->endOfDay(),
                $calendarIds
            );

            foreach ($busySlots as $busy) {
                $busyStart = $busy['start_dt'];
                $busyEnd = $busy['end_dt'];

                // Expand each busy range by the buffer on both sides so at least
                // $buffer minutes remain free between consecutive sessions.
                if ($slotStart < $busyEnd->copy()->addMinutes($buffer)
                    && $slotEnd > $busyStart->copy()->subMinutes($buffer)) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::warning("GoogleCalendar: Busy check failed, allowing booking: {$e->getMessage()}");
            return false; // Fail open
        }
    }
}
