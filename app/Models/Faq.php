<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $fillable = [
        'category',
        'question',
        'answer',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
    public function getQuestionAttribute($value): ?string
    {
        return $this->getTranslation('question', 'en');
    }

    public function getAnswerAttribute($value): ?string
    {
        return $this->getTranslation('answer', 'en');
    }
}
