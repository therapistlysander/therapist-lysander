# Payment Flow

## Current Status

**No payment processing is currently implemented.** The booking system is a request-based flow where clients book intro calls and the therapist manually manages scheduling.

## Future Considerations

If payment integration is added in the future, the following approach is recommended:

### Potential Flow

1. Client selects session type and time slot
2. Client is redirected to payment gateway (Mollie/Stripe)
3. Payment is processed
4. On success: booking is confirmed automatically
5. On failure: booking is held in `payment_pending` status
6. Webhook receives payment confirmation

### Recommended Provider

- **Mollie** — Popular in the Netherlands, supports iDEAL (Dutch bank payments)
- Alternative: **Stripe** — wider international support

### Implementation Notes

- Add `payment_status` field to `bookings` table
- Create webhook endpoint for payment callbacks
- Store transaction IDs for reference
- Handle refund scenarios (cancellation within policy period)
- Comply with Dutch/EU payment regulations

### Security Requirements

- Never store full card details
- Use provider's hosted checkout page
- Validate webhooks with signature verification
- Log all payment events for audit trail
