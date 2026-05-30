# Queue Workers

## Overview

The application uses Laravel's queue system with the **database** driver to process background jobs asynchronously. The primary use case is sending email notifications without blocking the HTTP response.

## Configuration

- **Driver:** `database` (configured in `.env` as `QUEUE_CONNECTION=database`)
- **Tables:** `jobs`, `job_batches`, `failed_jobs` (created via migration)
- **Default queue:** `default`

## Queued Jobs

### Email Notifications (via NotificationService)

| Job | Trigger |
|---|---|
| `BookingConfirmationMail` | Client submits a booking |
| `BookingApprovedMail` | Admin approves a booking |
| `BookingRejectedMail` | Admin rejects a booking |
| `ContactConfirmationMail` | Client submits contact form |
| `NewBookingAlertMail` | New booking → notify admin |
| `NewContactAlertMail` | New contact → notify admin |

All mail jobs are dispatched via `Mail::to(...)->queue(...)`.

## Running the Worker

### Development

```bash
# Part of the `composer dev` script (runs automatically)
php artisan queue:listen --tries=1 --timeout=0

# Or standalone
php artisan queue:work
```

### Production

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Recommended: Use **Supervisor** to keep the worker running:

```ini
[program:therapy-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
stopwaitsecs=3600
```

## Monitoring

```bash
# View failed jobs
php artisan queue:failed

# Retry a specific failed job
php artisan queue:retry <job-id>

# Retry all failed jobs
php artisan queue:retry all

# Flush all failed jobs
php artisan queue:flush

# Monitor queue sizes
php artisan queue:monitor
```

## Restart After Deployment

After deploying new code, always restart the queue worker so it picks up the latest changes:

```bash
php artisan queue:restart
```

This sends a graceful restart signal — the worker finishes its current job before restarting.

## Failure Handling

- Failed jobs are stored in the `failed_jobs` table
- Default retry attempts: 3 (production), 1 (development)
- Check `storage/logs/laravel.log` for failure details
- Common failures: SMTP connection issues, invalid email addresses
