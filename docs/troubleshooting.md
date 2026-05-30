# Troubleshooting

## Common Issues

### "Class not found" errors

```bash
composer dump-autoload
php artisan config:clear
```

### Views not updating

```bash
php artisan view:clear
# In development, ensure Vite is running: npm run dev
```

### Database migration errors

```bash
# Check migration status
php artisan migrate:status

# Reset and re-run (development only!)
php artisan migrate:fresh --seed

# If SQLite file is missing
touch database/database.sqlite
php artisan migrate
```

### Emails not sending

1. Check `.env` mail configuration (`MAIL_MAILER`, `MAIL_HOST`, etc.)
2. Check notification toggles in admin Settings → Email Settings
3. Verify queue worker is running: `php artisan queue:listen`
4. Check `failed_jobs` table for queued mail failures
5. In dev, use `MAIL_MAILER=log` and check `storage/logs/laravel.log`

### Admin login not working

1. Verify user exists: `php artisan tinker` → `User::where('email', '...')->first()`
2. Check `is_admin` flag is `true`
3. Clear session: `php artisan session:table` or clear browser cookies
4. Reset password: `php artisan tinker` → `User::first()->update(['password' => bcrypt('newpass')])`

### API returns 401 Unauthorized

1. Check `Authorization: Bearer {token}` header is included
2. Verify token exists in `personal_access_tokens` table
3. Token may have expired — re-login to get a new token
4. Check Sanctum configuration in `config/sanctum.php`

### Assets not loading (CSS/JS broken)

```bash
# Rebuild assets
npm run build

# Check manifest exists
ls public/build/manifest.json

# In development, start Vite dev server
npm run dev
```

### Queue jobs failing

```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear all failed jobs
php artisan queue:flush
```

### CORS issues (API)

- Check `config/cors.php` settings
- Ensure `allowed_origins` includes the frontend domain
- Verify `supports_credentials` is set correctly for Sanctum

### Storage/Permission errors

```bash
# Fix permissions (Linux/production)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Create storage link
php artisan storage:link
```

## Useful Artisan Commands

```bash
# List all routes
php artisan route:list

# Interactive REPL
php artisan tinker

# Real-time log viewer
php artisan pail

# Run code formatter
./vendor/bin/pint

# Run tests
php artisan test
```
