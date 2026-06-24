<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('preIntakeResponse')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        if ($request->filled('type')) {
            $query->where('session_type', $request->type);
        }

        $bookings = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Booking::count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        return view('admin.pages.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load('preIntakeResponse');
        return view('admin.pages.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        // Send notification if status actually changed
        if ($oldStatus !== $newStatus && $newStatus !== 'pending') {
            app(NotificationService::class)->sendBookingStatusChanged($booking, $newStatus);
        }

        return back()->with('success', 'Booking status updated.');
    }

    public function schedule(Request $request, Booking $booking)
    {
        $request->validate([
            'scheduled_at'     => 'required|date|after:now',
            'meeting_link'     => 'nullable|url|max:500',
            'meeting_platform' => 'nullable|in:zoom,google_meet,teams,whereby,other',
            'admin_notes'      => 'nullable|string|max:2000',
        ]);

        $booking->update([
            'scheduled_at'     => $request->scheduled_at,
            'meeting_link'     => $request->meeting_link,
            'meeting_platform' => $request->meeting_platform,
            'admin_notes'      => $request->admin_notes,
            'status'           => 'confirmed',
            'confirmed_at'     => now(),
        ]);

        app(NotificationService::class)->sendBookingApproved($booking);

        return back()->with('success', 'Session scheduled and booking confirmed.');
    }

    public function updateMeetingLink(Request $request, Booking $booking)
    {
        $request->validate([
            'meeting_link'     => 'required|url|max:500',
            'meeting_platform' => 'nullable|in:zoom,google_meet,teams,whereby,other',
        ]);

        $booking->update([
            'meeting_link'     => $request->meeting_link,
            'meeting_platform' => $request->meeting_platform,
        ]);

        return back()->with('success', 'Meeting link updated.');
    }

    public function approve(Request $request, Booking $booking)
    {
        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        app(NotificationService::class)->sendBookingApproved($booking);

        return back()->with('success', 'Booking approved.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status'           => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
        ]);

        app(NotificationService::class)->sendBookingRejected($booking);

        return back()->with('success', 'Booking rejected.');
    }

    public function destroy(Booking $booking)
    {
        $booking->preIntakeResponse?->delete();
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No bookings selected.');
        }

        // Delete associated pre-intake responses first
        \App\Models\PreIntakeResponse::whereIn('booking_id', $ids)->delete();
        Booking::whereIn('id', $ids)->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', count($ids) . ' booking(s) deleted.');
    }
}
