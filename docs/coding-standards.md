# Coding Standards

## PHP / Laravel

### Style
- Follow **PSR-12** coding standard
- Use **Laravel Pint** for automated formatting: `./vendor/bin/pint`
- Maximum line length: 120 characters
- Use strict comparison (`===`) unless intentional loose comparison

### Classes & Methods
- One class per file
- Methods should do one thing (Single Responsibility)
- Maximum method length: ~30 lines (refactor if longer)
- Use type declarations for parameters and return types

### Controllers
- Keep controllers thin — delegate business logic to services
- Use resource controllers where applicable
- Group related routes with meaningful prefixes
- Return appropriate HTTP status codes

### Models
- Define `$fillable` explicitly (never use `$guarded = []`)
- Define `$casts` for dates, booleans, JSON, and enums
- Use relationship methods with explicit return types
- Use query scopes for reusable filters

### Validation
- Use inline `$request->validate()` for simple cases
- Use Form Request classes for complex validation
- Always validate on the server — never trust client input

## Frontend

### Tailwind CSS
- Use utility-first classes
- Extract components for repeated patterns
- Follow mobile-first responsive design
- Use Tailwind's color palette consistently

### Blade Templates
- Use layouts and components for DRY templates
- Escape output with `{{ }}` (not `{!! !!}` unless necessary)
- Use `@csrf` in all forms
- Keep logic minimal in views — prepare data in controllers

### JavaScript
- Minimal JS — rely on Blade + Tailwind for UI
- Use Alpine.js or vanilla JS for interactivity where needed
- Keep scripts in `resources/js/`

## File Organization

```
Controllers/
├── FrontendController.php          # Public pages
├── ContactWebController.php        # Public contact form
├── BookingSubmitController.php      # Public booking form
├── Admin/                          # Admin panel (web)
│   ├── AdminDashboardController.php
│   ├── AdminBookingController.php
│   └── ...
└── Api/                            # API endpoints
    ├── AuthController.php
    ├── BookingController.php
    └── Admin/
        └── ...
```
