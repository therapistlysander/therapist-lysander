<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    public array $translatable = ['headline', 'body', 'quote'];

    protected $fillable = [
        'client_name',
        'client_title',
        'headline',
        'body',
        'tag',
        'quote',
        'rating',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];
}
