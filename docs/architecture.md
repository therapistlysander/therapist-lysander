# Architecture

## High-Level Architecture

```
┌─────────────────────────────────────────────────────┐
│                    Client Browser                     │
├──────────────────────┬──────────────────────────────┤
│   Public Site (SSR)  │      Admin Panel (SSR)        │
│   Blade Templates    │      Blade Templates          │
└──────────┬───────────┴──────────────┬───────────────┘
           │                          │
           ▼                          ▼
┌─────────────────────────────────────────────────────┐
│              Laravel Application                      │
├─────────────────────────────────────────────────────┤
│  Routes: web.php (SSR) + api.php (JSON API)          │
├─────────────────────────────────────────────────────┤
│  Controllers → Services → Models → Database          │
├─────────────────────────────────────────────────────┤
│  Queue (database driver) → Mail Jobs                 │
└─────────────────────────────────────────────────────┘
           │
           ▼
┌─────────────────────┐
│   SQLite / MySQL     │
└─────────────────────┘
```

## Routing Architecture

### Web Routes (`routes/web.php`)
- **Public pages:** Server-rendered Blade views (`FrontendController`)
- **Admin auth:** Login/logout/password reset (`AdminAuthController`)
- **Admin panel:** Full CRUD for all entities (session-authenticated)

### API Routes (`routes/api.php`)
- **Public endpoints:** Homepage data, testimonials, FAQs, settings
- **Public submissions:** Bookings, pre-intake, contact forms
- **Authenticated admin:** Full CRUD with Sanctum token auth

## Controller Layer

- `App\Http\Controllers\FrontendController` — Public page rendering
- `App\Http\Controllers\Admin\*` — Admin panel (web, session-based)
- `App\Http\Controllers\Api\*` — API endpoints (Sanctum token-based)

## Service Layer

- `App\Services\NotificationService` — Centralized email dispatch with toggles

## Model Layer

Key models and their relationships:
- `Booking` — Standalone booking requests
- `PreIntakeResponse` → belongs to `Booking` (optional)
- `ContactSubmission` → has many `ContactNote`
- `ContactNote` → belongs to `ContactSubmission`, belongs to `User`
- `BookingAvailability` — Weekly schedule slots
- `BookingBlockedDate` — Date-level blocks
- `BookingConfig` — System-wide booking settings
- `PageSection` — CMS content blocks
- `Testimonial`, `Faq`, `SeoSetting`, `SiteSetting`

## Middleware

- `auth` — Laravel session authentication
- `admin.web` — Checks `is_admin` flag for web routes
- `admin` — Checks `is_admin` flag for API routes
- `auth:sanctum` — Token-based API authentication

## Asset Pipeline

- Vite v8 with Laravel Vite Plugin
- Tailwind CSS v4 compiled via `@tailwindcss/vite`
- Assets served from `public/build/` in production
