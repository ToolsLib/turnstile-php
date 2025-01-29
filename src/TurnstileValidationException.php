<?php

namespace ToolsLib\TurnstilePhp;

use Exception;

class TurnstileValidationException extends Exception
{
    /**
     * TurnstileValidationException constructor.
     * 
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = 'Turnstile validation failed', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
