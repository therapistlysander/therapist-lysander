# AGENTS.md — Therapist Lysander Website

## Project Overview

This is a professional website and admin panel for Lysander Verschuur, a trauma-informed psychologist/therapist based in the Netherlands. The application handles client bookings, contact form submissions, pre-intake questionnaires, and content management via an admin panel.

## Tech Stack

- **Backend:** Laravel 13.x (PHP 8.3+)
- **Frontend:** Blade templates + Tailwind CSS v4, Vite v8
- **Database:** SQLite (development), MySQL/MariaDB (production)
- **Authentication:** Laravel Sanctum (API tokens) + session-based (admin panel)
- **Queue:** Laravel queue (database driver)
- **Media:** Spatie MediaLibrary, Intervention Image

## Architecture

- Standard Laravel MVC architecture
- Dual routing: web routes (Blade SSR admin + public site) and API v1 routes (Sanctum-protected)
- Service layer pattern (`App\Services\NotificationService`) for email dispatching
- Models located in `app/Models/`
- Admin controllers under `App\Http\Controllers\Admin\`
- API controllers under `App\Http\Controllers\Api\`

## Key Business Rules

1. **Booking Flow:** Clients submit booking requests → admin reviews → approves/rejects → client notified via email
2. **Pre-Intake:** A questionnaire linked to a booking, includes crisis risk screening
3. **Contact Form:** Submissions tracked with status (new → read → replied → archived), supports admin notes
4. **Notifications:** All emails are toggle-able via SiteSettings; system checks mail configuration before sending
5. **Availability:** Weekly schedule with time slots per day, supports blocked dates/overrides

## Directory Layout

```
app/
├── Http/Controllers/       # Web + API controllers
├── Mail/                   # Mailable classes (Admin + Client)
├── Models/                 # Eloquent models
├── Providers/              # Service providers
└── Services/               # Business logic services
config/                     # Laravel configuration
database/
├── migrations/             # Schema definitions
└── seeders/                # Seed data
resources/views/            # Blade templates
routes/
├── api.php                 # API v1 routes (Sanctum)
└── web.php                 # Web routes (public + admin)
public/                     # Compiled assets, images, fonts
```

## Conventions

- Follow PSR-12 coding style
- Use Laravel Pint for code formatting
- Models use `$fillable` (not `$guarded`)
- Status fields are string enums (e.g., `pending`, `reviewed`, `scheduled`)
- Email notifications are queued, not sent synchronously
- Admin routes use `admin.` name prefix
- API routes use `v1` prefix
