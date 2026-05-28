<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PreIntakeResponse;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Submit an intro call request (Step 1 + Step 3 of the booking flow).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name'         => ['required', 'string', 'max:100'],
            'last_name'          => ['required', 'string', 'max:100'],
            'email'              => ['required', 'email', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:30'],
            'session_type'       => ['nullable', 'string', 'in:online,in-person'],
            'preferred_language' => ['nullable', 'string', 'in:nl,en'],
            'reason'             => ['nullable', 'string', 'max:1000'],
            'preferred_date'     => ['nullable', 'date'],
        ]);

        $booking = Booking::create($validated);

        $notifications = app(NotificationService::class);
        $notifications->sendBookingConfirmation($booking);
        $notifications->alertAdminNewBooking($booking);

        return response()->json([
            'message'    => 'Your intro call request has been received. We will be in touch shortly.',
            'booking_id' => $booking->id,
        ], 201);
    }

    /**
     * Submit the pre-intake questionnaire (Step 2 of the booking flow).
     * Can be linked to an existing booking_id or stand-alone.
     */
    public function storePreIntake(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_id'            => ['nullable', 'integer', 'exists:bookings,id'],
            'first_name'            => ['required', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:30'],
            'age'                   => ['nullable', 'integer', 'min:16', 'max:100'],
            'gender'                => ['nullable', 'string', 'max:50'],
            'nationality'           => ['nullable', 'string', 'max:100'],
            'preferred_language'    => ['nullable', 'string', 'in:nl,en'],
            'presenting_issue'      => ['required', 'string', 'max:3000'],
            'previous_therapy'      => ['nullable', 'string', 'max:1000'],
            'previous_therapy_type' => ['nullable', 'string', 'max:255'],
            'current_medications'   => ['nullable', 'string', 'max:500'],
            'relevant_history'      => ['nullable', 'string', 'max:2000'],
            'crisis_risk'           => ['boolean'],
            'crisis_details'        => ['nullable', 'string', 'max:1000'],
            'session_preference'    => ['nullable', 'string', 'in:online,in-person'],
            'availability'          => ['nullable', 'array'],
            'additional_notes'      => ['nullable', 'string', 'max:1000'],
        ]);

        $response = PreIntakeResponse::create($validated);

        return response()->json([
            'message'     => 'Your questionnaire has been submitted. Thank you.',
            'response_id' => $response->id,
        ], 201);
    }
}
