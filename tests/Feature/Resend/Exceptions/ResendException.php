<?php

namespace Basement\BetterMails\Tests\Feature\Resend\Exceptions;

class ResendException extends \Exception
{
    public static function missingUuidHeader($message = 'Uuid mail not found on body request'): self
    {
        return new self(
            message: $message,
            code: 403
        );
    }
}
