<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait DataTableTrait
{
    /**
     * Apply search across multiple columns.
     */
    protected function applySearch(Builder $query, ?string $search, array $columns): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search, $columns) {
            foreach ($columns as $i => $col) {
                if ($i === 0) {
                    $q->where($col, 'like', "%{$search}%");
                } else {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            }
        });
    }

    /**
     * Apply a single WHERE filter.
     */
    protected function applyFilter(Builder $query, ?string $field, ?string $value): Builder
    {
        if (empty($field) || $value === null || $value === '') {
            return $query;
        }

        return $query->where($field, $value);
    }

    /**
     * Apply sorting with direction.
     *
     * @param Builder $query
     * @param string $defaultSort  Default column (e.g. 'created_at')
     * @param array  $sortable     Allowed sortable columns
     * @param Request|null $request
     * @return Builder
     */
    protected function applySort(Builder $query, string $defaultSort, array $sortable, ?Request $request = null): Builder
    {
        $request = $request ?? request();
        $sortField = $request->get('sort', $defaultSort);
        $sortDir = strtolower($request->get('dir', 'desc'));

        if (!in_array($sortField, $sortable)) {
            $sortField = $defaultSort;
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        return $query->orderBy($sortField, $sortDir);
    }

    /**
     * Paginate with per-page support.
     */
    protected function paginateResults(Builder $query, int $defaultPerPage = 10)
    {
        $perPage = (int) request()->get('per_page', $defaultPerPage);
        $allowed = [10, 25, 50, 100];

        if (!in_array($perPage, $allowed)) {
            $perPage = $defaultPerPage;
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get current sort direction for a column (for UI arrow indicators).
     */
    protected function sortDirection(string $column): ?string
    {
        if (request()->get('sort') === $column) {
            return request()->get('dir', 'desc');
        }
        return null;
    }

    /**
     * Get the opposite sort direction for toggle.
     */
    protected function toggleSortDir(string $column): string
    {
        if (request()->get('sort') === $column && request()->get('dir') === 'asc') {
            return 'desc';
        }
        return 'asc';
    }

    /**
     * Safely extract string value from translatable field.
     * Handles: string, array (from Spatie), JSON string, null.
     */
    protected function getTranslatableString($value, string $locale = 'en'): string
    {
        if ($value === null) {
            return '';
        }
        if (is_string($value)) {
            // Try to decode JSON
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded[$locale] ?? $decoded['en'] ?? '';
            }
            return $value;
        }
        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? '';
        }
        return (string) $value;
    }
}
