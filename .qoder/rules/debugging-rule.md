# Debugging Rules

## General Approach

1. **Reproduce first** — confirm the issue exists and is consistent
2. **Check logs** — `storage/logs/laravel.log` is the first source of truth
3. **Narrow scope** — isolate whether it's frontend, backend, database, or configuration
4. **Use Artisan tools** — `php artisan tinker`, `php artisan route:list`, `php artisan config:show`

## Common Debugging Commands

```bash
# View recent logs
php artisan pail

# Check route registration
php artisan route:list --name=admin

# Clear all caches
php artisan config:clear; php artisan cache:clear; php artisan view:clear; php artisan route:clear

# Test database connection
php artisan tinker --execute="DB::connection()->getPdo()"

# Check queue status
php artisan queue:monitor
```

## Email Issues

- Check `config/mail.php` and `.env` mail settings
- Use `MAIL_MAILER=log` to write emails to log instead of sending
- Check `NotificationService::isMailConfigured()` for mail prerequisite logic
- Verify notification toggles in `site_settings` table

## Database Issues

- SQLite file: `database/database.sqlite`
- Check migration status: `php artisan migrate:status`
- Reset database: `php artisan migrate:fresh --seed`
- Check foreign key constraints are enabled

## Authentication Issues

- Web admin uses session auth (`auth` + `admin.web` middleware)
- API uses Sanctum tokens (`auth:sanctum` middleware)
- Check token existence in `personal_access_tokens` table
- Verify `is_admin` flag on user record

## Frontend/Asset Issues

- Run `npm run build` to recompile assets
- Check Vite dev server is running during development
- Clear browser cache for CSS/JS changes
- Check `public/build/manifest.json` exists after build

## Environment

- Local development: Laragon on Windows
- Production: Linux server at `therapistlysander.kodeclouds.com`
- Always check `.env` values match expected environment
