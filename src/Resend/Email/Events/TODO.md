# Resend Events Implementation TODO

## Event Classes

- [ ] Create ResendEmailSentEvent
- [ ] Create ResendEmailAcceptedEvent
- [ ] Create ResendEmailClickedEvent
- [ ] Create ResendEmailComplainedEvent
- [ ] Create ResendEmailDeliveredEvent
- [ ] Create ResendEmailSoftBouncedEvent
- [ ] Create ResendEmailHardBouncedEvent
- [ ] Create ResendEmailOpenedEvent
- [ ] Create ResendEmailUnsubscribedEvent

## Webhook Handler

- [ ] Implement webhook signature verification for Resend
- [ ] Create webhook payload DTOs for each event type
- [ ] Implement webhook payload validation
- [ ] Add event type mapping from Resend to internal events
- [ ] Create event dispatcher for mapped events
