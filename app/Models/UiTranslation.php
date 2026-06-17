<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UiTranslation extends Model
{
    protected $fillable = [
        'group',
        'key',
        'locale',
        'value',
        'label',
    ];

    protected static bool $cacheLoaded = false;
    protected static array $cache = [];

    public function scopeForLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    public function scopeForGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public static function getTranslation(string $group, string $key, string $locale): ?string
    {
        $cacheKey = "{$group}.{$key}.{$locale}";
        
        if (!static::$cacheLoaded) {
            static::loadCache();
        }

        return static::$cache[$cacheKey] ?? null;
    }

    public static function loadAllForLocale(string $locale): array
    {
        $translations = static::where('locale', $locale)->get();
        $result = [];

        foreach ($translations as $translation) {
            if (!isset($result[$translation->group])) {
                $result[$translation->group] = [];
            }
            $result[$translation->group][$translation->key] = $translation->value;
        }

        return $result;
    }

    public static function loadCache(): void
    {
        if (static::$cacheLoaded) {
            return;
        }

        $translations = static::all();
        
        foreach ($translations as $translation) {
            $cacheKey = "{$translation->group}.{$translation->key}.{$translation->locale}";
            static::$cache[$cacheKey] = $translation->value;
        }

        static::$cacheLoaded = true;
    }

    public static function clearCache(): void
    {
        static::$cache = [];
        static::$cacheLoaded = false;
    }

    public static function getAllGroups(): array
    {
        return static::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();
    }

    public static function getKeysForGroup(string $group): \Illuminate\Support\Collection
    {
        return static::where('group', $group)
            ->orderBy('key')
            ->get();
    }
}
