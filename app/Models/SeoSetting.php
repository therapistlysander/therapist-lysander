<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SeoSetting extends Model
{
    use HasTranslations;

    public array $translatable = ['meta_title', 'meta_description', 'og_title', 'og_description'];

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];
}
