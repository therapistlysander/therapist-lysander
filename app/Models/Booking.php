<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'session_type',
        'session_format',
        'preferred_language',
        'reason',
        'source',
        'status',
        'admin_notes',
        'preferred_date',
        'scheduled_at',
        'meeting_link',
        'meeting_platform',
        'confirmed_at',
        'rejection_reason',
        'calendly_event_id',
    ];

    protected $casts = [
        'preferred_date' => 'datetime',
        'scheduled_at'   => 'datetime',
        'confirmed_at'   => 'datetime',
    ];

    public function preIntakeResponse(): HasOne
    {
        return $this->hasOne(PreIntakeResponse::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
