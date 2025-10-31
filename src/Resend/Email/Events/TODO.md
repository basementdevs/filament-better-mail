# Resend Events Implementation TODO

## Event Classes

- [x] Create ResendEmailSentEvent
- [ ] Create ResendEmailAcceptedEvent
- [x] Create ResendEmailClickedEvent
- [x] Create ResendEmailComplainedEvent
- [x] Create ResendEmailDeliveredEvent
- [x] Create ResendEmailHardBouncedEvent
- [x] Create ResendEmailOpenedEvent
- [ ] Create ResendEmailUnsubscribedEvent

## Webhook Handler

- [ ] Implement webhook signature verification for Resend
- [x] Create webhook payload DTOs for each event type
- [x] Implement webhook payload validation
- [ ] Add event type mapping from Resend to internal events
- [x] Create event dispatcher for mapped events
