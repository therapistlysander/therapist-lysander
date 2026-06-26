<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactSubmission;
use App\Models\Testimonial;
use App\Models\Faq;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'bookings_total'   => Booking::count(),
            'bookings_new'     => Booking::where('status', 'pending')->count(),
            'contacts_total'   => ContactSubmission::count(),
            'contacts_new'     => ContactSubmission::where('status', 'new')->count(),
            'testimonials'     => Testimonial::count(),
            'faqs'             => Faq::count(),
        ];

        $recentBookings = Booking::latest()->take(10)->get();
        $recentContacts = ContactSubmission::latest()->take(5)->get();

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

        return view('admin.pages.dashboard', compact(
            'stats', 'recentBookings', 'recentContacts',
            'statusChartData', 'bookingsOverTime', 'sessionTypeChartData'
        ));
    }
}
