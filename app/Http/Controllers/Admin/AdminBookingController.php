<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTableTrait;
use App\Jobs\SyncBookingToCalendarJob;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    use AdminTableTrait;

    public function index(Request $request)
    {
        $query = Booking::with('preIntakeResponse');

        // Search
        $this->applySearch($query, ['first_name', 'last_name', 'email'], $request->input('search'));

        // Filters
        $this->applyFilters($query, [
            'status' => 'status',
            'type'   => 'session_type',
        ]);

        // Sorting
        $this->applySort($query, ['first_name', 'status', 'session_type', 'created_at', 'scheduled_at'], 'created_at', 'desc');

        $bookings = $this->safePaginate($query->paginate($this->getPerPage($request)));

        $stats = [
            'total'     => Booking::count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'no_show'   => Booking::where('status', 'no_show')->count(),
        ];

        // Chart data: Bookings by status
        $statusChartData = [
            'labels' => ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No Show'],
            'data' => [
                Booking::where('status', 'pending')->count(),
                Booking::where('status', 'confirmed')->count(),
                Booking::where('status', 'completed')->count(),
                Booking::where('status', 'cancelled')->count(),
                Booking::where('status', 'no_show')->count(),
            ],
        ];

        // Chart data: Bookings over time (last 30 days)
        $bookingsOverTime = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $bookingsOverTime[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => Booking::whereDate('created_at', $date)->count(),
            ];
        }

        // Chart data: Session types
        $sessionTypes = Booking::select('session_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('session_type')
            ->pluck('count', 'session_type')
            ->toArray();

        $sessionTypeChartData = [
            'labels' => array_map(fn($type) => ucfirst($type ?? 'Not Set'), array_keys($sessionTypes)),
            'data' => array_values($sessionTypes),
        ];

        return view('admin.pages.bookings.index', compact(
            'bookings', 'stats', 'statusChartData', 'bookingsOverTime', 'sessionTypeChartData'
        ));
    }

    public function show(Booking $booking)
    {
        $booking->load('preIntakeResponse');
        $defaultMeetingLink = SiteSetting::where('key', 'default_meeting_link')->value('value');
        $defaultMeetingPlatform = SiteSetting::where('key', 'default_meeting_platform')->value('value');
        return view('admin.pages.bookings.show', compact('booking', 'defaultMeetingLink', 'defaultMeetingPlatform'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        // Confirming a booking locks in the requested date/time and applies the
        // site-wide default meeting room, so it is fully scheduled in one step.
        if ($newStatus === 'confirmed') {
            $this->ensureScheduledTime($booking);
            $this->applyDefaultMeetingLink($booking);
        }

        // Send notification if status actually changed
        if ($oldStatus !== $newStatus && $newStatus !== 'pending') {
            app(NotificationService::class)->sendBookingStatusChanged($booking, $newStatus);

            // Sync to Google Calendar based on status change
            $this->syncBookingToCalendar($booking->fresh(), $oldStatus, $newStatus);
        }

        return back()->with('success', 'Booking status updated.');
    }

    public function schedule(Request $request, Booking $booking)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'admin_notes'  => 'nullable|string|max:2000',
        ]);

        $booking->update([
            'scheduled_at' => $request->scheduled_at,
            'admin_notes'  => $request->admin_notes,
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Online sessions always use the site-wide default meeting room.
        $this->applyDefaultMeetingLink($booking);

        app(NotificationService::class)->sendBookingApproved($booking->fresh());

        // Sync to Google Calendar
        SyncBookingToCalendarJob::dispatch($booking->fresh(), 'create');

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

        // Single-step confirm: lock in the client's requested date/time and the
        // site-wide default meeting room, then send one confirmation email
        // containing all the session details (date, platform, meeting link).
        $this->ensureScheduledTime($booking);
        $this->applyDefaultMeetingLink($booking);

        app(NotificationService::class)->sendBookingApproved($booking->fresh());

        // Sync to Google Calendar now that a scheduled time is set.
        if ($booking->fresh()->scheduled_at) {
            SyncBookingToCalendarJob::dispatch($booking->fresh(), 'create');
        }

        return back()->with('success', 'Booking confirmed — session scheduled with your default meeting link and confirmation email sent.');
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

        // Remove Google Calendar event if exists
        if ($booking->google_event_id) {
            SyncBookingToCalendarJob::dispatch($booking->fresh(), 'delete');
        }

        return back()->with('success', 'Booking rejected.');
    }

    public function destroy(Booking $booking)
    {
        // Remove Google Calendar event if exists
        if ($booking->google_event_id) {
            SyncBookingToCalendarJob::dispatch($booking, 'delete');
        }

        $booking->preIntakeResponse?->delete();
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $action = $request->input('action', 'delete');
        if ($action !== 'delete') {
            return back()->with('error', 'Unsupported bulk action.');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No bookings selected.');
        }

        // Dispatch calendar deletion for bookings with Google events
        $bookingsWithEvents = Booking::whereIn('id', $ids)->whereNotNull('google_event_id')->get();
        foreach ($bookingsWithEvents as $booking) {
            SyncBookingToCalendarJob::dispatch($booking, 'delete');
        }

        // Delete associated pre-intake responses first
        \App\Models\PreIntakeResponse::whereIn('booking_id', $ids)->delete();
        Booking::whereIn('id', $ids)->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', count($ids) . ' booking(s) deleted.');
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

    /**
     * Lock in the client's requested date/time as the scheduled time when the
     * booking has not been explicitly scheduled yet. This lets a single
     * "Confirm" action fully schedule the session without a separate step.
     */
    private function ensureScheduledTime(Booking $booking): void
    {
        if (! $booking->scheduled_at && $booking->preferred_date) {
            $booking->update(['scheduled_at' => $booking->preferred_date]);
        }
    }

    /**
     * Apply the site-wide default online meeting link/platform to a booking.
     * All online sessions use the single default room managed in Site Settings,
     * so there is no per-booking link to enter.
     */
    private function applyDefaultMeetingLink(Booking $booking): void
    {
        $link = SiteSetting::where('key', 'default_meeting_link')->value('value');

        if (! $link) {
            return;
        }

        $platform = SiteSetting::where('key', 'default_meeting_platform')->value('value');

        $booking->update([
            'meeting_link'     => $link,
            'meeting_platform' => $platform ?: $booking->meeting_platform,
        ]);
    }
}
