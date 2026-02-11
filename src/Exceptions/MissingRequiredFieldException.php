<?php

namespace Payhere\Exceptions;

class MissingRequiredFieldException extends PayhereException
{
    /**
     * Create a new MissingRequiredFieldException instance
     *
     * @param string $fieldName
     * @param string|null $customMessage
     */
    public function __construct(string $fieldName, ?string $customMessage = null)
    {
        $message = $customMessage ?? "Required field '{$fieldName}' is missing";
        
        parent::__construct($message, 400, null, [
            'field' => $fieldName
        ]);
    }
}