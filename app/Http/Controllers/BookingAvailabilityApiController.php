<?php

namespace App\Http\Controllers;

use App\Models\BookingAvailability;
use App\Models\BookingBlockedDate;
use App\Models\BookingConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingAvailabilityApiController extends Controller
{
    /**
     * GET /api/availability/slots?date=2026-05-28
     *
     * Returns available time slots for a given date.
     * Slots are auto-generated from config (duration + hours + break).
     */
    public function slots(Request $request): JsonResponse
    {
        $dateStr = $request->query('date');

        if (!$dateStr || !strtotime($dateStr)) {
            return response()->json(['error' => 'Invalid date parameter'], 400);
        }

        $date = \Carbon\Carbon::parse($dateStr);
        $dayOfWeek = $date->dayOfWeekIso - 1; // 0=Monday..6=Sunday

        // Get the schedule for this day
        $schedule = BookingAvailability::where('day_of_week', $dayOfWeek)->first();

        if (!$schedule || !$schedule->is_active) {
            return response()->json([
                'date'      => $dateStr,
                'available' => false,
                'slots'     => [],
                'message'   => 'No availability on this day.',
            ]);
        }

        // Get global config
        $config = BookingConfig::settings();

        // Use per-day overrides if set, otherwise use global defaults
        $startTime = $schedule->start_time ?: $config->default_start_time;
        $endTime = $schedule->end_time ?: $config->default_end_time;

        // Generate slots dynamically
        $slots = BookingConfig::generateSlots(
            $startTime,
            $endTime,
            $config->slot_duration,
            $config->break_start,
            $config->break_end
        );

        // Check if the whole date is blocked or specific slots blocked
        $blocked = BookingBlockedDate::where('blocked_date', $date->toDateString())->first();

        if ($blocked) {
            if ($blocked->blocked_slots === null) {
                return response()->json([
                    'date'      => $dateStr,
                    'available' => false,
                    'slots'     => [],
                    'message'   => 'This date is fully blocked.',
                ]);
            }

            // Remove specific blocked slots
            $slots = array_values(array_diff($slots, $blocked->blocked_slots));
        }

        return response()->json([
            'date'      => $dateStr,
            'available' => count($slots) > 0,
            'slots'     => array_values($slots),
        ]);
    }

    /**
     * GET /api/availability/schedule
     *
     * Returns inactive days and fully blocked dates for the calendar UI.
     */
    public function schedule(): JsonResponse
    {
        $schedule = BookingAvailability::orderBy('day_of_week')->get();
        $blockedDates = BookingBlockedDate::where('blocked_date', '>=', now()->toDateString())
            ->where('blocked_date', '<=', now()->addDays(60)->toDateString())
            ->get();

        // Days that are inactive (0=Mon..6=Sun)
        $inactiveDays = $schedule->where('is_active', false)->pluck('day_of_week')->values();

        // Fully blocked dates (where blocked_slots is null)
        $fullyBlocked = $blockedDates->whereNull('blocked_slots')->pluck('blocked_date')
            ->map(fn($d) => $d->format('Y-m-d'))->values();

        return response()->json([
            'inactive_days'       => $inactiveDays,
            'fully_blocked_dates' => $fullyBlocked,
        ]);
    }
}
