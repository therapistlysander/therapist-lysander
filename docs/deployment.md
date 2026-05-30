# Deployment

## Environments

| Environment | URL | Database | Server |
|---|---|---|---|
| Local | `http://therapy.test` | SQLite | Laragon (Windows) |
| Production | `https://therapistlysander.kodeclouds.com` | MySQL | Linux VPS |

## Production Server

- **Path:** `/home/kodeclouds-therapistlysander/htdocs/therapistlysander.kodeclouds.com`
- **PHP:** 8.3+
- **Web Server:** Apache/Nginx

## Deployment Steps

### First-Time Setup

```bash
# Clone repository
git clone <repo-url> .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --ignore-scripts
npm run build

# Environment
cp .env.example .env
php artisan key:generate
# Edit .env with production values

# Database
php artisan migrate --force
php artisan db:seed

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Subsequent Deployments

```bash
# Pull latest code
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install --ignore-scripts
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue worker
php artisan queue:restart
```

## Environment Variables (Production)

Key `.env` settings for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://therapistlysander.kodeclouds.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=therapy
DB_USERNAME=<db_user>
DB_PASSWORD=<db_password>

MAIL_MAILER=smtp
MAIL_HOST=<smtp_host>
MAIL_PORT=587
MAIL_USERNAME=<smtp_user>
MAIL_PASSWORD=<smtp_password>
MAIL_ENCRYPTION=tls

QUEUE_CONNECTION=database
```

## Queue Worker

In production, run the queue worker as a supervised process:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Use systemd or supervisor to keep it running.

## Local Development

```bash
# Start all services (server, queue, logs, vite)
composer dev

# Or individually:
php artisan serve
php artisan queue:listen
npm run dev
```
