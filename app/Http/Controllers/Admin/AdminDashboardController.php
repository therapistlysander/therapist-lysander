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

        $recentBookings = Booking::latest()->take(5)->get();
        $recentContacts = ContactSubmission::latest()->take(5)->get();

        return view('admin.pages.dashboard', compact('stats', 'recentBookings', 'recentContacts'));
    }
}
