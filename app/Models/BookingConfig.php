<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingConfig extends Model
{
    protected $table = 'booking_config';

    protected $fillable = [
        'slot_duration',
        'default_start_time',
        'default_end_time',
        'break_start',
        'break_end',
        'buffer_minutes',
    ];

    /**
     * Get the singleton config row.
     */
    public static function settings(): self
    {
        return static::first() ?? new static([
            'slot_duration'      => 30,
            'default_start_time' => '09:00',
            'default_end_time'   => '16:00',
            'break_start'        => '12:00',
            'break_end'          => '13:30',
            'buffer_minutes'     => 15,
        ]);
    }

    /**
     * Generate time slots based on start, end, duration, and break.
     */
    public static function generateSlots(
        string $startTime,
        string $endTime,
        int $duration,
        ?string $breakStart = null,
        ?string $breakEnd = null
    ): array {
        $slots = [];
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $bStart = $breakStart ? strtotime($breakStart) : null;
        $bEnd = $breakEnd ? strtotime($breakEnd) : null;

        $current = $start;
        while ($current + ($duration * 60) <= $end) {
            $slotEnd = $current + ($duration * 60);

            // Skip if slot overlaps with break
            if ($bStart && $bEnd) {
                if ($current < $bEnd && $slotEnd > $bStart) {
                    $current = $bEnd;
                    continue;
                }
            }

            $slots[] = date('H:i', $current);
            $current += $duration * 60;
        }

        return $slots;
    }
}
