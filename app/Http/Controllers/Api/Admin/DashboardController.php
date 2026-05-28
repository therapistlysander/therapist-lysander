<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactSubmission;
use App\Models\PreIntakeResponse;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'bookings' => [
                'total'    => Booking::count(),
                'pending'  => Booking::where('status', 'pending')->count(),
                'this_week'=> Booking::where('created_at', '>=', now()->startOfWeek())->count(),
            ],
            'pre_intake' => [
                'total'       => PreIntakeResponse::count(),
                'pending'     => PreIntakeResponse::where('status', 'pending')->count(),
                'crisis_risk' => PreIntakeResponse::where('crisis_risk', true)
                                    ->where('status', 'pending')
                                    ->count(),
            ],
            'contacts' => [
                'total'     => ContactSubmission::count(),
                'new'       => ContactSubmission::where('status', 'new')->count(),
                'this_week' => ContactSubmission::where('created_at', '>=', now()->startOfWeek())->count(),
            ],
            'testimonials' => [
                'total'    => Testimonial::count(),
                'active'   => Testimonial::where('is_active', true)->count(),
                'featured' => Testimonial::where('is_featured', true)->count(),
            ],
        ]);
    }
}
