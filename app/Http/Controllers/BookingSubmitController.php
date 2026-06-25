<?php

namespace App\Http\Controllers;

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

        // Double-booking prevention: check if slot is already taken
        $existingBooking = Booking::where('preferred_date', $preferredDate)
            ->whereIn('status', ['pending', 'confirmed', 'scheduled'])
            ->first();

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

        // Create the booking
        $booking = Booking::create([
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'email'          => $request->input('email'),
            'session_type'   => $request->input('type'),
            'session_format' => $request->input('format'),
            'preferred_date' => $preferredDate,
            'reason'         => $request->input('notes'),
            'source'         => 'website',
            'status'         => 'pending',
            'client_timezone' => $request->input('client_timezone'),
        ]);

        // Create pre-intake response if any pre-intake data provided
        $supportAreas = $request->input('support_areas', []);
        $piBrings     = $request->input('pi_brings');
        $piTherapy    = $request->input('pi_therapy');
        $piComm       = $request->input('pi_comm');
        $piExpect     = $request->input('pi_expect');
        $piNotes      = $request->input('pi_notes');

        if ($piBrings || !empty($supportAreas) || $piTherapy || $piComm || $piExpect || $piNotes) {
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
        }

        $notifications = app(NotificationService::class);
        $notifications->sendBookingConfirmation($booking);
        $notifications->alertAdminNewBooking($booking);

        return response()->json([
            'success'    => true,
            'booking_id' => $booking->id,
            'message'    => 'Booking request submitted successfully.',
        ], 201);
    }

    /**
     * Check if the requested slot conflicts with Google Calendar.
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

            $slotStart = Carbon::parse($preferredDate);
            $slotEnd = $slotStart->copy()->addMinutes($config->slot_duration);

            return $calendar->isSlotBusy($slotStart, $slotEnd, $token->calendar_id);
        } catch (\Throwable $e) {
            Log::warning("GoogleCalendar: Busy check failed, allowing booking: {$e->getMessage()}");
            return false; // Fail open
        }
    }
}
