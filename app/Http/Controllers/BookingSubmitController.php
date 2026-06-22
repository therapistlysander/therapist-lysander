<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PreIntakeResponse;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
