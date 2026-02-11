<?php

namespace Payhere\Exceptions;

class InvalidPaymentDataException extends PayhereException
{
    /**
     * Create a new InvalidPaymentDataException instance
     *
     * @param string $message
     * @param array $context
     */
    public function __construct(
        string $message = "Invalid payment data provided",
        array $context = []
    ) {
        parent::__construct($message, 400, null, $context);
    }
}