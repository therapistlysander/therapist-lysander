# Testing Rules

## Framework

- Use PHPUnit 12.x for all tests
- Run tests via `composer test` or `php artisan test`
- Tests are located in `tests/Feature/` and `tests/Unit/`

## Test Organization

- **Unit tests:** Test individual classes/methods in isolation (models, services)
- **Feature tests:** Test HTTP endpoints, full request/response cycle
- Name test methods descriptively: `test_booking_requires_email_field()`

## Database

- Use `RefreshDatabase` trait for feature tests
- Use SQLite in-memory for fast test execution
- Seed only what's needed per test — avoid `DatabaseSeeder` in tests

## Assertions

- Assert HTTP status codes explicitly
- Assert JSON structure for API responses
- Assert database state after mutations (`assertDatabaseHas`, `assertDatabaseMissing`)
- Assert emails are queued when expected (`Mail::assertQueued`)

## Best Practices

- Each test should be independent — no shared state between tests
- Test both happy paths and error cases
- Test validation rules (missing fields, invalid formats)
- Test authentication/authorization (unauthenticated, non-admin)
- Mock external services (mail, third-party APIs)
- Keep tests fast — avoid unnecessary setup

## Coverage Priorities

1. Booking submission + status transitions
2. Contact form submission + notes
3. Pre-intake questionnaire with crisis screening
4. Admin authentication and authorization
5. Availability slot calculation
