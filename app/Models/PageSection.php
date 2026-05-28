<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page',
        'section_key',
        'label',
        'content',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'content'   => 'array',
        'is_active' => 'boolean',
    ];
}
