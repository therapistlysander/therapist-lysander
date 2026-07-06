<?php

namespace App\Translation;

use App\Models\UiTranslation;
use Illuminate\Translation\FileLoader;

class DatabaseTranslationLoader extends FileLoader
{
    protected bool $dbLoaded = false;
    protected array $dbOverrides = [];

    public function load($locale, $group, $namespace = null): array
    {
        // Load file-based translations first
        $fileTranslations = parent::load($locale, $group, $namespace);

        // Apply DB overrides for non-package translations
        // ('*' is Laravel's default namespace for non-package calls like __('ui.nav.fees'))
        if ($namespace === '*' || $namespace === null) {
            $this->loadDbOverrides();

            // DB stores records with group='nav', key='home'
            // But __('ui.nav.home') calls load() with group='ui'
            // So we need to nest DB overrides under the file group name
            if (isset($this->dbOverrides[$locale])) {
                $nested = [];
                foreach ($this->dbOverrides[$locale] as $dbGroup => $keys) {
                    // Nest: dbOverrides['nav']['home'] -> ['nav' => ['home' => value]]
                    $nested[$dbGroup] = $keys;
                }
                $fileTranslations = array_replace_recursive($fileTranslations, $nested);
            }
        }

        return $fileTranslations;
    }

    protected function loadDbOverrides(): void
    {
        if ($this->dbLoaded) {
            return;
        }

        try {
            if (!\Schema::hasTable('ui_translations')) {
                $this->dbLoaded = true;
                return;
            }

            $translations = UiTranslation::all();

            foreach ($translations as $translation) {
                $locale = $translation->locale;
                $group = $translation->group;
                $key = $translation->key;
                $value = $translation->value;

                if (!isset($this->dbOverrides[$locale])) {
                    $this->dbOverrides[$locale] = [];
                }

                if (!isset($this->dbOverrides[$locale][$group])) {
                    $this->dbOverrides[$locale][$group] = [];
                }

                // Support nested keys with dot notation (e.g., "home.how_it_works")
                $keys = explode('.', $key);
                $ref = &$this->dbOverrides[$locale][$group];
                
                foreach ($keys as $i => $k) {
                    if ($i === count($keys) - 1) {
                        $ref[$k] = $value;
                    } else {
                        if (!isset($ref[$k]) || !is_array($ref[$k])) {
                            $ref[$k] = [];
                        }
                        $ref = &$ref[$k];
                    }
                }

                // Reset reference to avoid corruption across iterations
                unset($ref);
            }
        } catch (\Throwable $e) {
            // Silently fail if DB not available yet (e.g., during migration)
        }

        $this->dbLoaded = true;
    }
}
