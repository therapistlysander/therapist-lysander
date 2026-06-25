<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleCalendarToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'calendar_id',
        'google_email',
        'is_active',
        'connected_at',
        'last_synced_at',
        'last_error',
    ];

    protected $casts = [
        'access_token'     => 'encrypted',
        'refresh_token'    => 'encrypted',
        'token_expires_at' => 'integer',
        'is_active'        => 'boolean',
        'connected_at'     => 'datetime',
        'last_synced_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token needs refreshing (expired or within 60 seconds of expiry).
     */
    public function needsRefresh(): bool
    {
        return $this->token_expires_at <= (time() + 60);
    }
}
