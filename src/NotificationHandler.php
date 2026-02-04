<?php

namespace Payhere;

/**
 * Handle PayHere payment notifications
 */
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
     * Create a new NotificationHandler instance
     *
     * @param Config $config
     * @param array|null $postData POST data from PayHere (defaults to $_POST)
     */
    public function __construct(Config $config, ?array $postData = null)
    {
        $this->config = $config;
        $this->data = $postData ?? $_POST;
    }
    
    /**
     * Verify the notification hash
     *
     * @return bool
     */
    public function verify(): bool
    {
        if (!isset($this->data['md5sig'])) {
            return false;
        }
        
        $merchantId = $this->data['merchant_id'] ?? '';
        $orderId = $this->data['order_id'] ?? '';
        $amount = $this->data['payhere_amount'] ?? '';
        $currency = $this->data['payhere_currency'] ?? '';
        $statusCode = $this->data['status_code'] ?? '';
        $md5sig = $this->data['md5sig'];
        
        $merchantSecret = $this->config->getMerchantSecret();
        $hashedSecret = strtoupper(md5($merchantSecret));
        
        $localHash = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $amount . 
                $currency . 
                $statusCode . 
                $hashedSecret
            )
        );
        
        return $localHash === $md5sig;
    }
    
    /**
     * Check if payment was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->verify() && 
               isset($this->data['status_code']) && 
               (int)$this->data['status_code'] === self::STATUS_SUCCESS;
    }
    
    /**
     * Get the payment status code
     *
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return isset($this->data['status_code']) ? (int)$this->data['status_code'] : null;
    }
    
    /**
     * Get the payment status as a string
     *
     * @return string
     */
    public function getStatusText(): string
    {
        $statusCode = $this->getStatusCode();
        
        switch ($statusCode) {
            case self::STATUS_SUCCESS:
                return 'Success';
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_CANCELED:
                return 'Canceled';
            case self::STATUS_FAILED:
                return 'Failed';
            case self::STATUS_CHARGEDBACK:
                return 'Chargedback';
            default:
                return 'Unknown';
        }
    }
    
    /**
     * Get the order ID
     *
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->data['order_id'] ?? null;
    }
    
    /**
     * Get the payment ID from PayHere
     *
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->data['payment_id'] ?? null;
    }
    
    /**
     * Get the payment amount
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return isset($this->data['payhere_amount']) ? (float)$this->data['payhere_amount'] : null;
    }
    
    /**
     * Get the payment currency
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->data['payhere_currency'] ?? null;
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
     * Get the card number (masked)
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
     * Get all notification data
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
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
}
