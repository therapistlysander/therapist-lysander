# Business Logic

## Booking Flow

1. Client visits `/booking` and selects an available time slot
2. Client fills in personal details + reason for booking
3. System creates a `Booking` record with status `pending`
4. Client receives confirmation email (if enabled)
5. Admin receives alert email (if enabled)
6. Admin reviews booking in admin panel
7. Admin can:
   - **Approve** → status changes to `scheduled`, client notified with meeting details
   - **Reject** → status changes to `cancelled`, client notified with reason
   - **Schedule** → set specific date/time + meeting link

### Booking Statuses
- `pending` — New submission, awaiting admin review
- `reviewed` — Admin has seen it but not actioned
- `scheduled` — Approved and session time confirmed
- `completed` — Session took place
- `cancelled` — Rejected or cancelled by client

## Pre-Intake Questionnaire

1. Client fills out detailed questionnaire (health history, presenting issues)
2. System creates `PreIntakeResponse` record (optionally linked to a booking)
3. **Crisis screening:** If `crisis_risk` is flagged, admin is immediately alerted
4. Admin reviews responses in admin panel
5. Status: `pending` → `reviewed` → `archived`

## Contact Form

1. Client submits message via `/contact`
2. System creates `ContactSubmission` with status `new`
3. Client receives acknowledgment email
4. Admin receives alert email
5. Admin can:
   - Change status (`new` → `read` → `replied` → `archived`)
   - Add internal notes (stored in `contact_notes`)

## Notification System

All notifications flow through `NotificationService`:
- Each notification type has an independent toggle in `site_settings`
- System checks if mail is properly configured before attempting to send
- All emails are dispatched via queue (never synchronously)
- Log driver is treated as valid for development environments

### Notification Types
| Setting Key | Description |
|---|---|
| `notify_contact_confirmation` | Confirm receipt to client |
| `notify_booking_confirmation` | Confirm booking to client |
| `notify_booking_approved` | Inform client of approval |
| `notify_booking_rejected` | Inform client of rejection |
| `notify_admin_new_contact` | Alert admin of new contact |
| `notify_admin_new_booking` | Alert admin of new booking |

## Availability System

- Admin defines weekly schedule (per day-of-week, with time slots)
- Admin can block specific dates (entire day or specific slots)
- Public booking form fetches available slots via API
- Slots already booked are excluded from availability
