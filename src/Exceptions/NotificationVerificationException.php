<?php

namespace Payhere\Exceptions;

class NotificationVerificationException extends PayhereException
{
    /**
     * Create a new NotificationVerificationException instance
     *
     * @param string $message
     * @param array $context
     */
    public function __construct(
        string $message = "Payment notification verification failed",
        array $context = []
    ) {
        parent::__construct($message, 403, null, $context);
    }
}