<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreIntakeResponse extends Model
{
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'age',
        'gender',
        'nationality',
        'preferred_language',
        'presenting_issue',
        'brings_to_therapy',
        'support_areas',
        'communication_style',
        'duration_expectation',
        'previous_therapy',
        'previous_therapy_type',
        'current_medications',
        'relevant_history',
        'crisis_risk',
        'crisis_details',
        'session_preference',
        'availability',
        'additional_notes',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'availability'  => 'array',
        'support_areas' => 'array',
        'crisis_risk'   => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
