<?php

namespace Payhere;

use Payhere\Exceptions\NotificationVerificationException;
use Payhere\Exceptions\MissingRequiredFieldException;


class NotificationHandler
{
    private Config $config;
    private array $data;
    
    /**
     * Payment status codes
     */
    const STATUS_SUCCESS = 2;
    const STATUS_PENDING = 0;
    const STATUS_CANCELED = -1;
    const STATUS_FAILED = -2;
    const STATUS_CHARGEDBACK = -3;
    
    /**
     * Required fields in notification data
     */
    private const REQUIRED_FIELDS = [
        'merchant_id',
        'order_id',
        'payment_id',
        'payhere_amount',
        'payhere_currency',
        'status_code',
        'md5sig'
    ];
    
    /**
     * Create a new NotificationHandler instance
     *
     * @param Config $config Configuration instance
     * @param array|null $postData POST data from PayHere (defaults to $_POST)
     * @throws MissingRequiredFieldException If required fields are missing
     */
    public function __construct(Config $config, ?array $postData = null)
    {
        $this->config = $config;
        $this->data = $postData ?? $_POST;
        
        // Validate that all required fields are present
        $this->validateRequiredFields();
    }
    
    /**
     * Validate that notification contains all required fields
     *
     * @throws MissingRequiredFieldException
     */
    private function validateRequiredFields(): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
                throw new MissingRequiredFieldException(
                    $field,
                    "Notification data missing required field: {$field}"
                );
            }
        }
    }
    
    /**
     * Verify the notification hash signature
     *
     * @return bool
     * @throws NotificationVerificationException If verification fails
     */
    public function verify(): bool
    {
        $merchantId = $this->data['merchant_id'];
        $orderId = $this->data['order_id'];
        $amount = $this->data['payhere_amount'];
        $currency = $this->data['payhere_currency'];
        $statusCode = $this->data['status_code'];
        $receivedHash = $this->data['md5sig'];
        
        // Verify merchant ID matches
        if ($merchantId !== $this->config->getMerchantId()) {
            throw new NotificationVerificationException(
                'Merchant ID mismatch - notification may be for different merchant',
                [
                    'expected_merchant_id' => $this->config->getMerchantId(),
                    'received_merchant_id' => $merchantId,
                    'order_id' => $orderId
                ]
            );
        }
        
        // Generate expected hash
        $merchantSecret = $this->config->getMerchantSecret();
        $hashedSecret = strtoupper(md5($merchantSecret));
        $amountFormatted = number_format((float)$amount, 2, '.', '');
        
        $expectedHash = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $amountFormatted . 
                $currency . 
                $statusCode . 
                $hashedSecret
            )
        );
        
        // Use timing-safe comparison
        if (!hash_equals($expectedHash, $receivedHash)) {
            throw new NotificationVerificationException(
                'Hash verification failed - possible tampering or invalid signature',
                [
                    'order_id' => $orderId,
                    'payment_id' => $this->data['payment_id'],
                    'expected_hash' => $expectedHash,
                    'received_hash' => $receivedHash,
                    'amount' => $amountFormatted,
                    'currency' => $currency,
                    'status_code' => $statusCode
                ]
            );
        }
        
        return true;
    }
    
    /**
     * Check if payment was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return (int)$this->data['status_code'] === self::STATUS_SUCCESS;
    }
    
    /**
     * Check if payment is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return (int)$this->data['status_code'] === self::STATUS_PENDING;
    }
    
    /**
     * Check if payment was canceled
     *
     * @return bool
     */
    public function isCanceled(): bool
    {
        return (int)$this->data['status_code'] === self::STATUS_CANCELED;
    }
    
    /**
     * Check if payment failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return (int)$this->data['status_code'] === self::STATUS_FAILED;
    }
    
    /**
     * Check if payment was charged back
     *
     * @return bool
     */
    public function isChargedBack(): bool
    {
        return (int)$this->data['status_code'] === self::STATUS_CHARGEDBACK;
    }
    
    /**
     * Get the payment status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return (int)$this->data['status_code'];
    }
    
    /**
     * Get the payment status as human-readable text
     *
     * @return string
     */
    public function getStatusText(): string
    {
        $statusCode = $this->getStatusCode();
        
        $statuses = [
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CHARGEDBACK => 'Chargedback'
        ];
        
        return $statuses[$statusCode] ?? 'Unknown';
    }
    
    /**
     * Get the order ID
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->data['order_id'];
    }
    
    /**
     * Get the PayHere payment ID
     *
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->data['payment_id'];
    }
    
    /**
     * Get the payment amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return (float)$this->data['payhere_amount'];
    }
    
    /**
     * Get the payment currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->data['payhere_currency'];
    }
    
    /**
     * Get merchant ID from notification
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->data['merchant_id'];
    }
    
    /**
     * Get custom field 1
     *
     * @return string|null
     */
    public function getCustom1(): ?string
    {
        return $this->data['custom_1'] ?? null;
    }
    
    /**
     * Get custom field 2
     *
     * @return string|null
     */
    public function getCustom2(): ?string
    {
        return $this->data['custom_2'] ?? null;
    }
    
    /**
     * Get the card holder name
     *
     * @return string|null
     */
    public function getCardHolderName(): ?string
    {
        return $this->data['card_holder_name'] ?? null;
    }
    
    /**
     * Get the masked card number
     *
     * @return string|null
     */
    public function getCardNo(): ?string
    {
        return $this->data['card_no'] ?? null;
    }
    
    /**
     * Get the payment method
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->data['method'] ?? null;
    }
    
    /**
     * Get status message from PayHere
     *
     * @return string|null
     */
    public function getStatusMessage(): ?string
    {
        return $this->data['status_message'] ?? null;
    }
    
    /**
     * Get all notification data as array
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
    
    /**
     * Get a specific field from notification data
     *
     * @param string $key Field name
     * @param mixed $default Default value if field doesn't exist
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Check if a field exists in notification data
     *
     * @param string $key Field name
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Get a formatted summary of the payment notification
     *
     * @return array
     */
    public function getSummary(): array
    {
        return [
            'order_id' => $this->getOrderId(),
            'payment_id' => $this->getPaymentId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'status' => $this->getStatusText(),
            'status_code' => $this->getStatusCode(),
            'is_success' => $this->isSuccess(),
            'method' => $this->getMethod(),
            'card_holder' => $this->getCardHolderName(),
            'custom_1' => $this->getCustom1(),
            'custom_2' => $this->getCustom2(),
        ];
    }
    
    /**
     * Convert notification to JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->getSummary(), JSON_PRETTY_PRINT);
    }
    
    /**
     * Log notification data (useful for debugging)
     *
     * @param string $logFile Path to log file
     * @return bool
     */
    public function log(string $logFile): bool
    {
        $logEntry = sprintf(
            "[%s] %s - Order: %s, Payment: %s, Amount: %s %s, Status: %s\n",
            date('Y-m-d H:i:s'),
            $this->getStatusText(),
            $this->getOrderId(),
            $this->getPaymentId(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getStatusCode()
        );
        
        return file_put_contents($logFile, $logEntry, FILE_APPEND) !== false;
    }
}