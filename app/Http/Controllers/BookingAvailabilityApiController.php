<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingAvailability;
use App\Models\BookingBlockedDate;
use App\Models\BookingConfig;
use App\Models\GoogleCalendarToken;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        // Get configured timezone (fallback to app timezone)
        $tzSetting = \App\Models\SiteSetting::where('key', 'timezone')->first();
        $timezone = $tzSetting?->value ?: config('app.timezone', 'UTC');

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

        // Subtract slots that already have pending/confirmed bookings (double-booking prevention)
        $bookedSlots = Booking::whereDate('preferred_date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed', 'scheduled'])
            ->pluck('preferred_date')
            ->map(fn($dt) => $dt->format('H:i'))
            ->toArray();

        $slots = array_values(array_diff($slots, $bookedSlots));

        // Subtract Google Calendar busy slots (if connected)
        $googleBusySlots = $this->getGoogleBusySlots($date, $timezone);
        if (!empty($googleBusySlots)) {
            $slots = array_values(array_diff($slots, $googleBusySlots));
        }

        // Filter out past time slots if the requested date is today
        $now = \Carbon\Carbon::now($timezone);
        if ($date->toDateString() === $now->toDateString()) {
            $currentTime = $now->format('H:i');
            $slots = array_values(array_filter($slots, function ($slot) use ($currentTime) {
                return $slot > $currentTime;
            }));
        }

        return response()->json([
            'date'      => $dateStr,
            'timezone'  => $timezone,
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

    /**
     * Get Google Calendar busy slots for a given date.
     * Returns array of 'H:i' strings to subtract from available slots.
     * Fails open: returns empty array if Google API is unavailable.
     */
    private function getGoogleBusySlots(Carbon $date, string $timezone): array
    {
        $token = GoogleCalendarToken::where('is_active', true)->first();
        if (!$token) {
            return [];
        }

        $cacheKey = "gcal_busy_{$date->toDateString()}";
        $cacheTtl = config('google-calendar.cache_ttl', 300);

        $busySlots = Cache::remember($cacheKey, now()->addSeconds($cacheTtl), function () use ($token, $date, $timezone) {
            try {
                $calendar = app(GoogleCalendarService::class);
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();

                return $calendar->getBusySlots($start, $end, $token->calendar_id);
            } catch (\Throwable $e) {
                Log::warning("GoogleCalendar: Failed to fetch busy slots for {$date->toDateString()}: {$e->getMessage()}");
                return []; // Fail open
            }
        });

        // Convert busy time ranges to slot times (H:i format)
        return $this->busySlotsToSlotTimes($busySlots, $date);
    }

    /**
     * Convert Google Calendar busy time ranges to slot start times (H:i format).
     * A slot is considered busy if any part of it overlaps with a busy period.
     */
    private function busySlotsToSlotTimes(array $busySlots, Carbon $date): array
    {
        if (empty($busySlots)) {
            return [];
        }

        $config = BookingConfig::settings();
        $slotDuration = $config->slot_duration;
        $busySlotTimes = [];

        // Get all possible slot times for the day
        $allSlots = BookingConfig::generateSlots(
            $config->default_start_time,
            $config->default_end_time,
            $slotDuration,
            $config->break_start,
            $config->break_end
        );

        foreach ($allSlots as $slot) {
            $slotStart = Carbon::parse($date->toDateString() . ' ' . $slot);
            $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

            foreach ($busySlots as $busy) {
                $busyStart = $busy['start_dt'] ?? Carbon::parse($date->toDateString() . ' ' . $busy['start']);
                $busyEnd = $busy['end_dt'] ?? Carbon::parse($date->toDateString() . ' ' . $busy['end']);

                // Check overlap
                if ($slotStart < $busyEnd && $slotEnd > $busyStart) {
                    $busySlotTimes[] = $slot;
                    break;
                }
            }
        }

        return $busySlotTimes;
    }
}
