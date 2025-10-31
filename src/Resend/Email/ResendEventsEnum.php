<?php

namespace Basement\BetterMails\Resend\Email;

enum ResendEventsEnum: string
{
    case EmailSent = 'email.sent';
    case EmailDelivered = 'email.delivered';
    case EmailDeliveryDelayed = 'email.delivery_delayed';
    case EmailComplained = 'email.complained';
    case EmailBounced = 'email.bounced';
    case EmailOpened = 'email.opened';
    case EmailClicked = 'email.clicked';
    case EmailReceived = 'email.received';
    case EmailFailed = 'email.failed';
}
