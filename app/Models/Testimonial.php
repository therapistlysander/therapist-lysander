<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
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
     * Translatable fields stored as JSON: {"en": "...", "nl": "..."}
     */
    protected array $translatableFields = ['headline', 'short_description', 'body', 'quote'];

    /**
     * Get translation for a field in the given locale.
     */
    public function getTranslation(string $field, string $locale = 'en'): ?string
    {
        $value = $this->getRawOriginal($field);
        if ($value === null) {
            return null;
        }
        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? null;
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded[$locale] ?? $decoded['en'] ?? null;
            }
            return $value;
        }
        return null;
    }

    /**
     * Set translation for a field.
     */
    public function setTranslation(string $field, string $locale, ?string $value): self
    {
        $current = $this->getRawOriginal($field);
        $translations = [];

        if (is_string($current)) {
            $decoded = json_decode($current, true);
            if (is_array($decoded)) {
                $translations = $decoded;
            }
        } elseif (is_array($current)) {
            $translations = $current;
        }

        $translations[$locale] = $value ?? '';
        $this->attributes[$field] = json_encode($translations, JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * Get the English translation (default accessor).
     */
    public function getHeadlineAttribute($value): ?string
    {
        return $this->getTranslation('headline', 'en');
    }

    public function getShortDescriptionAttribute($value): ?string
    {
        return $this->getTranslation('short_description', 'en');
    }

    public function getBodyAttribute($value): ?string
    {
        return $this->getTranslation('body', 'en');
    }

    public function getQuoteAttribute($value): ?string
    {
        return $this->getTranslation('quote', 'en');
    }

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
