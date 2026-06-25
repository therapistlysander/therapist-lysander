<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncBookingToCalendarJob;
use App\Models\Booking;
use App\Models\PreIntakeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::with('preIntakeResponse')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(20));
    }

    public function show(Booking $booking): JsonResponse
    {
        return response()->json($booking->load('preIntakeResponse'));
    }

    public function updateStatus(Request $request, Booking $booking): JsonResponse
    {
        $validated = $request->validate([
            'status'      => ['required', 'string', 'in:pending,reviewed,scheduled,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // Sync to Google Calendar on status change
        if ($oldStatus !== $validated['status']) {
            $this->syncBookingToCalendar($booking->fresh(), $oldStatus, $validated['status']);
        }

        return response()->json($booking);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        // Remove Google Calendar event if exists
        if ($booking->google_event_id) {
            SyncBookingToCalendarJob::dispatch($booking, 'delete');
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted.']);
    }

    // Pre-intake responses

    public function preIntakeIndex(Request $request): JsonResponse
    {
        $query = PreIntakeResponse::with('booking')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('crisis_risk')) {
            $query->where('crisis_risk', (bool) $request->crisis_risk);
        }

        return response()->json($query->paginate(20));
    }

    public function preIntakeShow(PreIntakeResponse $preIntakeResponse): JsonResponse
    {
        return response()->json($preIntakeResponse->load('booking'));
    }

    public function preIntakeUpdateStatus(Request $request, PreIntakeResponse $preIntakeResponse): JsonResponse
    {
        $validated = $request->validate([
            'status'      => ['required', 'string', 'in:pending,reviewed,archived'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $preIntakeResponse->update($validated);

        return response()->json($preIntakeResponse);
    }

    /**
     * Determine the correct calendar sync action based on status transition.
     */
    private function syncBookingToCalendar(Booking $booking, string $oldStatus, string $newStatus): void
    {
        match (true) {
            $newStatus === 'confirmed' && $booking->scheduled_at =>
                SyncBookingToCalendarJob::dispatch($booking, 'create'),
            $newStatus === 'cancelled' && $booking->google_event_id =>
                SyncBookingToCalendarJob::dispatch($booking, 'delete'),
            default => null,
        };
    }
}
