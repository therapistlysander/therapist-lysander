# Security Rules

## Authentication & Authorization

- Admin panel is protected by session auth (`auth` middleware) + `admin.web` middleware
- API is protected by Sanctum token auth (`auth:sanctum` middleware)
- Admin routes require `is_admin` flag on user model
- Never expose admin functionality to non-admin users

## Input Validation

- Validate ALL user input on the server side — never rely on client validation alone
- Use Laravel Form Requests for complex validation
- Sanitize HTML input to prevent XSS (use `strip_tags()` or HTML Purifier where needed)
- Validate file uploads: check MIME type, file size, and extension

## CSRF Protection

- All web forms must include `@csrf` directive
- API routes use Sanctum tokens (CSRF not applicable for token-based auth)
- Never disable CSRF middleware globally

## Data Protection

- Never log sensitive data (passwords, tokens, personal health info)
- Use `bcrypt` (Laravel default) for password hashing
- Mask email addresses in non-admin-facing responses
- Pre-intake responses contain sensitive health data — restrict access to admin only

## SQL Injection Prevention

- Always use Eloquent ORM or query builder (parameterized queries)
- Never concatenate raw user input into SQL strings
- Use `whereIn()` with arrays, not raw string interpolation

## File Upload Security

- Store uploads outside the public directory when possible
- Use Spatie MediaLibrary for managed file handling
- Validate MIME types server-side (don't trust `Content-Type` headers)
- Generate unique filenames — never use user-provided filenames directly

## Rate Limiting

- Rate limit login attempts (`throttle:5,1`)
- Rate limit public form submissions to prevent spam
- Rate limit API endpoints to prevent abuse

## Environment & Secrets

- Never commit `.env` to version control
- Use `APP_DEBUG=false` in production
- Rotate Sanctum tokens periodically
- Use HTTPS in production (enforce via middleware or server config)

## Headers

- Set `X-Content-Type-Options: nosniff`
- Set `X-Frame-Options: DENY` (unless embedding is needed)
- Set `Strict-Transport-Security` in production
- Remove `X-Powered-By` header
