<?php

namespace Basement\BetterMails\Core\Exceptions;

use Exception;

class MailException extends Exception
{
    public static function missingUuidHeader($message = 'Uuid mail signature not found on body request'): self
    {
        return new self(
            message: $message,
            code: 403
        );
    }
}
