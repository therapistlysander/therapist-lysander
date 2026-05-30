# Performance Rules

## Database Queries

- **Prevent N+1 queries:** Always eager-load relationships with `with()` in controllers
- **Use pagination:** Never load unbounded collections; use `paginate()` or `simplePaginate()`
- **Index critical columns:** Ensure `status`, `email`, `created_at` columns are indexed
- **Select only needed columns:** Use `select()` when full model hydration isn't needed

## Caching

- Cache site settings (they rarely change): use `Cache::remember()` with TTL
- Cache SEO metadata per page key
- Clear relevant caches when admin updates settings
- Use `config:cache` and `route:cache` in production

## Queue & Jobs

- All email notifications MUST be queued (`->queue()`) — never sent inline
- Use appropriate queue timeouts and retry limits
- Monitor failed jobs table periodically

## Assets & Frontend

- Use Vite for tree-shaking and code splitting
- Optimize images before upload (Intervention Image handles resizing)
- Leverage browser caching via proper cache headers
- Use `@vite` directive for cache-busted asset URLs

## Eloquent Best Practices

- Avoid loading full models for counts: use `->count()` directly
- Use `chunk()` or `cursor()` for processing large datasets
- Prefer `updateOrCreate` over separate find + update calls
- Use database-level constraints (unique, foreign keys) instead of application checks

## Production Optimizations

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --optimize-autoloader --no-dev
```
