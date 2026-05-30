# Always Apply Rules

## Code Style

- Follow PSR-12 coding standard for all PHP code
- Use Laravel Pint formatting (run `./vendor/bin/pint` before committing)
- Use strict types where applicable
- Prefer early returns over deep nesting

## Naming Conventions

- **Controllers:** PascalCase, suffixed with `Controller` (e.g., `BookingController`)
- **Models:** Singular PascalCase (e.g., `Booking`, `ContactSubmission`)
- **Migrations:** Snake_case with descriptive name
- **Routes:** Kebab-case URIs, dot-notation names (e.g., `admin.bookings.index`)
- **Views:** Dot-notation blade paths (e.g., `admin.bookings.index`)

## Eloquent Models

- Always use `$fillable` array (never `$guarded = []`)
- Define relationships explicitly with return types
- Use query scopes for reusable filters
- Cast dates, booleans, and JSON fields in `$casts`

## Error Handling

- Never expose stack traces in production
- Log errors with appropriate severity levels
- Return user-friendly error messages in API responses
- Use Laravel's validation for all form inputs

## Security

- Always validate and sanitize user input
- Use parameterized queries (Eloquent handles this)
- Never commit `.env` files or secrets
- Use CSRF protection for all web forms
- Use Sanctum for API authentication

## Git Practices

- Write meaningful commit messages
- Don't commit `vendor/`, `node_modules/`, or compiled assets
- Keep migrations forward-only in production
