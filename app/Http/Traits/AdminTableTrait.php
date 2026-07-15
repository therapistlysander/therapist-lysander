<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AdminTableTrait
{
    /**
     * Apply search across specified columns using WHERE LIKE.
     */
    protected function applySearch(Builder $query, array $columns, ?string $search): void
    {
        if (empty($search)) return;

        $query->where(function ($q) use ($columns, $search) {
            foreach ($columns as $col) {
                $q->orWhere($col, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * Apply sorting from request params (?sort=col&direction=asc|desc).
     */
    protected function applySort(Builder $query, array $sortable, string $default = 'id', string $defaultDir = 'desc'): void
    {
        $sort = request('sort');
        $direction = strtolower(request('direction', $defaultDir));

        if ($sort && in_array($sort, $sortable, true)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy($default, $defaultDir);
        }
    }

    /**
     * Apply simple equality filters from request (?status=pending, ?category=x, etc.).
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $param => $column) {
            $value = request($param);
            if (is_string($value) && $value !== '') {
                $query->where($column, $value);
            }
        }
    }

    /**
     * Get validated per-page value from request.
     */
    protected function getPerPage(Request $request, int $default = 10): int
    {
        $allowed = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', $default);

        return in_array($perPage, $allowed, true) ? $perPage : $default;
    }

    /**
     * Helper: get sort link URL preserving all current query params.
     */
    protected function sortUrl(string $column): string
    {
        $currentSort = request('sort');
        $currentDir = request('direction', 'desc');
        $newDir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';

        return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDir, 'page' => null]);
    }

    /**
     * Helper: check if a column is currently sorted.
     */
    protected function isSorted(string $column): bool
    {
        return request('sort') === $column;
    }

    /**
     * Helper: get current sort direction for a column.
     */
    protected function sortDirection(string $column): ?string
    {
        return $this->isSorted($column) ? request('direction', 'desc') : null;
    }

    /**
     * Append only safe (scalar) query params to paginator for URL generation.
     */
    protected function safePaginate($paginator)
    {
        $safe = array_filter(request()->query(), fn($v) => is_scalar($v) || is_null($v));
        return $paginator->appends($safe);
    }
}
