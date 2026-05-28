<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
    ];

    public function getValueAttribute($value): mixed
    {
        return match ($this->type) {
            'boolean' => (bool) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }
}
