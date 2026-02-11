<?php

namespace Payhere\Exceptions;

use Exception;

class PayhereException extends Exception
{
    /**
     * Additional context data for the exception
     *
     * @var array
     */
    protected $context = [];

    /**
     * Create a new PayhereException instance
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @param array $context
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the exception context data
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set additional context data
     *
     * @param array $context
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }
}