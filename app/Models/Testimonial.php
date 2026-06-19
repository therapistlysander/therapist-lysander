<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    public array $translatable = ['headline', 'short_description', 'body', 'quote'];

    protected $fillable = [
        'client_name',
        'client_title',
        'headline',
        'short_description',
        'body',
        'tag',
        'type',
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

    /**
     * Scope: client testimonials only.
     */
    public function scopeClient($query)
    {
        return $query->where('type', 'client');
    }

    /**
     * Scope: professional endorsements only.
     */
    public function scopeEndorsement($query)
    {
        return $query->where('type', 'endorsement');
    }

    /**
     * Scope: featured testimonials.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: active testimonials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
