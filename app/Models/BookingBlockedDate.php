<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingBlockedDate extends Model
{
    protected $fillable = [
        'blocked_date',
        'blocked_slots',
        'reason',
    ];

    protected $casts = [
        'blocked_date'  => 'date',
        'blocked_slots' => 'array',
    ];
}
