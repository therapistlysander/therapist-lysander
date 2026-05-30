# Database

## Connection

- **Development:** SQLite (`database/database.sqlite`)
- **Production:** MySQL/MariaDB (configured via `.env`)
- Default connection: `sqlite` (set in `config/database.php`)

## Tables

### Core Tables

| Table | Purpose |
|---|---|
| `users` | Admin user accounts |
| `personal_access_tokens` | Sanctum API tokens |
| `cache` | Cache storage (database driver) |
| `jobs` / `failed_jobs` | Queue job storage |

### Booking System

| Table | Purpose |
|---|---|
| `bookings` | Client booking/intro call requests |
| `pre_intake_responses` | Detailed pre-intake questionnaire submissions |
| `booking_availabilities` | Weekly schedule (one row per day of week) |
| `booking_blocked_dates` | Specific date blocks/overrides |
| `booking_configs` | System-wide booking configuration |

### Contact System

| Table | Purpose |
|---|---|
| `contact_submissions` | Client contact form messages |
| `contact_notes` | Admin notes on contact submissions |

### Content Management

| Table | Purpose |
|---|---|
| `page_sections` | CMS content blocks per page |
| `testimonials` | Client testimonials |
| `faqs` | Frequently asked questions |
| `seo_settings` | Per-page SEO metadata |
| `site_settings` | Key-value site-wide settings |

## Key Relationships

```
Booking (1) ←→ (0..1) PreIntakeResponse
ContactSubmission (1) ←→ (many) ContactNote
ContactNote (many) → (1) User
BookingAvailability — standalone (unique day_of_week)
BookingBlockedDate — standalone (unique blocked_date)
```

## Status Enums

### Booking Status
`pending` → `reviewed` → `scheduled` → `completed` | `cancelled`

### Contact Status
`new` → `read` → `replied` → `archived`

### Pre-Intake Status
`pending` → `reviewed` → `archived`

## Migrations

Migrations are sequential and located in `database/migrations/`:
1. `create_users_table` — Users, password resets, sessions
2. `create_cache_table` — Cache store
3. `create_jobs_table` — Queue jobs
4. `create_personal_access_tokens_table` — Sanctum tokens
5. `create_content_tables` — Page sections, SEO, site settings
6. `create_testimonials_table` — Testimonials
7. `create_booking_tables` — Bookings + pre-intake responses
8. `create_contact_tables` — Contact submissions + notes
9. `create_faqs_table` — FAQs
10. `add_scheduling_fields_to_bookings_table` — Meeting link, scheduled time
11. `create_booking_availability_tables` — Availability + blocked dates
12. `create_booking_config_table` — Booking configuration
13. `add_pre_intake_fields` — Additional pre-intake columns

## Seeders

- `AdminUserSeeder` — Creates default admin account
- `BookingAvailabilitySeeder` — Default weekly schedule
- `PageSectionSeeder` — Default page content
- `SeoSettingSeeder` — Default SEO metadata
- `SiteSettingSeeder` — Default site settings
- `TestimonialSeeder` — Sample testimonials
- `FaqSeeder` — Default FAQ entries
