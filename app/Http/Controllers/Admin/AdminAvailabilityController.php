<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingAvailability;
use App\Models\BookingBlockedDate;
use App\Models\BookingConfig;
use Illuminate\Http\Request;

class AdminAvailabilityController extends Controller
{
    public function index()
    {
        $config = BookingConfig::settings();
        $schedule = BookingAvailability::orderBy('day_of_week')->get()->keyBy('day_of_week');
        $blockedDates = BookingBlockedDate::where('blocked_date', '>=', now()->toDateString())
            ->orderBy('blocked_date')->get();

        // Generate preview slots based on current config
        $previewSlots = BookingConfig::generateSlots(
            $config->default_start_time,
            $config->default_end_time,
            $config->slot_duration,
            $config->break_start,
            $config->break_end
        );

        return view('admin.pages.availability.index', compact('config', 'schedule', 'blockedDates', 'previewSlots'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'slot_duration'      => 'required|integer|in:15,20,30,45,50,60,90,120',
            'buffer_minutes'     => 'required|integer|in:0,5,10,15,20,30,45,60',
            'default_start_time' => 'required|date_format:H:i',
            'default_end_time'   => 'required|date_format:H:i|after:default_start_time',
            'break_start'        => 'nullable|date_format:H:i',
            'break_end'          => 'nullable|date_format:H:i|after:break_start',
        ]);

        $config = BookingConfig::first();
        if (!$config) {
            $config = new BookingConfig();
        }

        $config->fill($request->only([
            'slot_duration', 'buffer_minutes', 'default_start_time', 'default_end_time', 'break_start', 'break_end'
        ]));

        // Allow clearing break
        if (!$request->filled('break_start')) {
            $config->break_start = null;
            $config->break_end = null;
        }

        $config->save();

        return redirect()->route('admin.availability.index')->with('success', 'Schedule settings saved successfully.');
    }

    public function updateSchedule(Request $request)
    {
        $days = $request->input('days', []);

        foreach (range(0, 6) as $dayOfWeek) {
            $data = $days[$dayOfWeek] ?? [];
            $isActive = isset($data['is_active']);

            // Per-day time overrides (optional)
            $startTime = (!empty($data['start_time']) && preg_match('/^\d{2}:\d{2}$/', $data['start_time']))
                ? $data['start_time'] : null;
            $endTime = (!empty($data['end_time']) && preg_match('/^\d{2}:\d{2}$/', $data['end_time']))
                ? $data['end_time'] : null;

            BookingAvailability::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'is_active'  => $isActive,
                    'start_time' => $startTime,
                    'end_time'   => $endTime,
                    'time_slots' => [], // No longer manually managed
                ]
            );
        }

        return redirect()->route('admin.availability.index')->with('success', 'Working days updated.');
    }

    public function storeBlockedDate(Request $request)
    {
        $request->validate([
            'blocked_date' => 'required|date|after_or_equal:today',
            'reason'       => 'nullable|string|max:255',
            'block_type'   => 'required|in:full_day,specific_slots',
            'blocked_slots'=> 'nullable|string',
        ]);

        $slots = null;
        if ($request->input('block_type') === 'specific_slots') {
            $slotsRaw = $request->input('blocked_slots', '');
            if ($slotsRaw) {
                $slots = array_values(array_filter(
                    array_map('trim', explode(',', $slotsRaw)),
                    fn($s) => preg_match('/^\d{2}:\d{2}$/', $s)
                ));
                if (empty($slots)) $slots = null;
            }
        }

        BookingBlockedDate::updateOrCreate(
            ['blocked_date' => $request->input('blocked_date')],
            [
                'blocked_slots' => $slots,
                'reason'        => $request->input('reason'),
            ]
        );

        return redirect()->route('admin.availability.index')->with('success', 'Blocked date added.');
    }

    public function destroyBlockedDate(BookingBlockedDate $blockedDate)
    {
        $blockedDate->delete();
        return redirect()->route('admin.availability.index')->with('success', 'Blocked date removed.');
    }
}
