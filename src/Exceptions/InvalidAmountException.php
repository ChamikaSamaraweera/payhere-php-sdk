<?php

namespace Payhere\Exceptions;

class InvalidAmountException extends PayhereException
{
    /**
     * Create a new InvalidAmountException instance
     *
     * @param string $message
     * @param float|null $amount
     */
    public function __construct(
        string $message = "Invalid payment amount",
        ?float $amount = null
    ) {
        $context = $amount !== null ? ['amount' => $amount] : [];
        parent::__construct($message, 400, null, $context);
    }
}