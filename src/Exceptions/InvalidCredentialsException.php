<?php

namespace Payhere\Exceptions;

class InvalidCredentialsException extends PayhereException
{
    /**
     * Create a new InvalidCredentialsException instance
     *
     * @param string $message
     * @param array $context
     */
    public function __construct(
        string $message = "Invalid PayHere merchant credentials provided",
        array $context = []
    ) {
        parent::__construct($message, 401, null, $context);
    }
}