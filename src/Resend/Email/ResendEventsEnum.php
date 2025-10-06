<?php

namespace Basement\BetterMails\Resend\Email;

enum ResendEventsEnum: string
{
    case Email_Sent = 'email.sent';
    case Email_Delivered = 'email.delivered';
    case Email_Delivery_Delayed = 'email.delivery_delayed';
    case Email_Complained = 'email.complained';
    case Email_Bounced = 'email.bounced';
    case Email_Opened = 'email.opened';
    case Email_Clicked = 'email.clicked';
    case Email_Received = 'email.received';
    case Email_Failed = 'email.failed';
}
