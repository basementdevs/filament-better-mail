<?php

namespace Basement\BetterMails\Core\Enums;

enum MailEventType: string
{
    // Initial internal event when the email is created
    case Sent = 'sent';

    // Events that can be triggered by the webhooks
    case Accepted = 'accepted';
    case Clicked = 'clicked';
    case Complained = 'complained';
    case Delivered = 'delivered';
    case SoftBounced = 'soft_bounced';
    case HardBounced = 'hard_bounced';
    case Opened = 'opened';
    case Unsubscribed = 'unsubscribed';
}
