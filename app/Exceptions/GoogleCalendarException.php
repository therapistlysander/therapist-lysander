<?php

namespace App\Exceptions;

use RuntimeException;

class GoogleCalendarException extends RuntimeException
{
    public static function tokenExpired(string $message = 'Google Calendar token has expired or been revoked.'): self
    {
        return new self($message);
    }

    public static function apiError(string $message, int $code = 0): self
    {
        return new self("Google Calendar API error: {$message}", $code);
    }

    public static function notConnected(string $message = 'Google Calendar is not connected.'): self
    {
        return new self($message);
    }
}
