<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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

        $booking->update($validated);

        return response()->json($booking);
    }

    public function destroy(Booking $booking): JsonResponse
    {
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
}
