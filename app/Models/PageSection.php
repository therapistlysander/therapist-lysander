<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PageSection extends Model
{
    use HasTranslations;

    public array $translatable = ['content'];

    protected $fillable = [
        'page',
        'section_key',
        'label',
        'content',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
