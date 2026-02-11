<?php

namespace Payhere\Exceptions;

class InvalidCurrencyException extends PayhereException
{
    /**
     * List of supported currencies
     */
    private const SUPPORTED_CURRENCIES = ['LKR', 'USD', 'GBP', 'EUR', 'AUD'];

    /**
     * Create a new InvalidCurrencyException instance
     *
     * @param string $currency
     */
    public function __construct(string $currency)
    {
        $message = sprintf(
            "Invalid currency '%s'. Supported currencies: %s",
            $currency,
            implode(', ', self::SUPPORTED_CURRENCIES)
        );
        
        parent::__construct($message, 400, null, [
            'currency' => $currency,
            'supported_currencies' => self::SUPPORTED_CURRENCIES
        ]);
    }
}