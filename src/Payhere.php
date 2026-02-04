<?php

namespace Payhere;

/**
 * Main PayHere SDK class
 */
class Payhere
{
    private Config $config;
    
    /**
     * Create a new Payhere instance
     *
     * @param string $merchantId
     * @param string $merchantSecret
     * @param bool $isSandbox
     */
    public function __construct(string $merchantId, string $merchantSecret, bool $isSandbox = true)
    {
        $this->config = new Config($merchantId, $merchantSecret, $isSandbox);
    }
    
    /**
     * Create a new payment request
     *
     * @return PaymentRequest
     */
    public function createPaymentRequest(): PaymentRequest
    {
        return new PaymentRequest($this->config);
    }
    
    /**
     * Handle payment notification
     *
     * @param array|null $postData
     * @return NotificationHandler
     */
    public function handleNotification(?array $postData = null): NotificationHandler
    {
        return new NotificationHandler($this->config, $postData);
    }
    
    /**
     * Get the configuration
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
}
