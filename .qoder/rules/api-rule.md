# API Rules

## Route Structure

- All API routes are prefixed with `/api/v1`
- Public routes require no authentication
- Authenticated routes use `auth:sanctum` middleware
- Admin routes additionally use `admin` middleware

## Response Format

All API responses should follow a consistent JSON structure:

```json
// Success
{
  "data": { ... },
  "message": "Optional success message"
}

// Error
{
  "message": "Human-readable error description",
  "errors": { "field": ["Validation error"] }
}
```

## HTTP Status Codes

- `200` — Successful GET/PATCH/PUT
- `201` — Successful POST (resource created)
- `204` — Successful DELETE (no content)
- `401` — Unauthenticated
- `403` — Forbidden (authenticated but not admin)
- `404` — Resource not found
- `422` — Validation failed

## Authentication

- Use Sanctum token-based auth for API consumers
- Tokens are issued via `POST /api/v1/auth/login`
- Include token in `Authorization: Bearer {token}` header
- Logout invalidates current token via `POST /api/v1/auth/logout`

## Validation

- Always use Form Requests or inline `$request->validate()`
- Return 422 with field-level errors for validation failures
- Validate all input — never trust client data

## Pagination

- List endpoints should support pagination via `?page=` and `?per_page=`
- Default per_page: 15, maximum: 100
- Use Laravel's `paginate()` method for consistent response structure

## Rate Limiting

- Public form submissions (booking, contact, pre-intake) should be rate-limited
- Apply throttle middleware: `throttle:6,1` for form submissions
