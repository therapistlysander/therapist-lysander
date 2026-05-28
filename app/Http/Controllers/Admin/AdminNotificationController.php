<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNotificationController extends Controller
{
    public function recent(): JsonResponse
    {
        // Get recent bookings (last 7 days)
        $bookings = Booking::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => 'booking_' . $booking->id,
                    'type' => 'booking',
                    'icon' => '&#128197;',
                    'text' => "New booking from <strong>{$booking->first_name} {$booking->last_name}</strong>",
                    'time' => $booking->created_at->diffForHumans(),
                    'url' => route('admin.bookings.show', $booking),
                    'is_read' => $booking->status !== 'pending',
                    'created_at' => $booking->created_at,
                ];
            });

        // Get recent contacts (last 7 days)
        $contacts = ContactSubmission::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => 'contact_' . $contact->id,
                    'type' => 'contact',
                    'icon' => '&#9993;',
                    'text' => "New message from <strong>{$contact->name}</strong>",
                    'time' => $contact->created_at->diffForHumans(),
                    'url' => route('admin.contacts.show', $contact),
                    'is_read' => $contact->status !== 'new',
                    'created_at' => $contact->created_at,
                ];
            });

        // Merge and sort by date
        $notifications = $bookings->concat($contacts)
            ->sortByDesc('created_at')
            ->take(15)
            ->values()
            ->toArray();

        // Count unread
        $unreadCount = Booking::where('status', 'pending')->count()
            + ContactSubmission::where('status', 'new')->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markRead(): JsonResponse
    {
        // Mark all pending bookings as reviewed
        Booking::where('status', 'pending')
            ->update(['status' => 'reviewed']);

        // Mark all new contacts as read
        ContactSubmission::where('status', 'new')
            ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }
}
