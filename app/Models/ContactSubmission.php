<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'source',
        'status',
        'admin_notes',
        'ip_address',
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(ContactNote::class);
    }
}
