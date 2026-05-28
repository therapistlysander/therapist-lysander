<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAvailability extends Model
{
    protected $table = 'booking_availabilities';

    protected $fillable = [
        'day_of_week',
        'time_slots',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'time_slots' => 'array',
        'is_active'  => 'boolean',
    ];

    public const DAYS = [
        0 => 'Monday',
        1 => 'Tuesday',
        2 => 'Wednesday',
        3 => 'Thursday',
        4 => 'Friday',
        5 => 'Saturday',
        6 => 'Sunday',
    ];

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Unknown';
    }
}
