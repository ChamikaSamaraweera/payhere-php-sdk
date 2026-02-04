<?php

namespace Payhere;

/**
 * Configuration class for PayHere SDK
 */
class Config
{
    const SANDBOX_URL = 'https://sandbox.payhere.lk/pay/checkout';
    const LIVE_URL = 'https://www.payhere.lk/pay/checkout';
    
    private string $merchantId;
    private string $merchantSecret;
    private bool $isSandbox;
    
    /**
     * Create a new Config instance
     *
     * @param string $merchantId Your PayHere Merchant ID
     * @param string $merchantSecret Your PayHere Merchant Secret
     * @param bool $isSandbox Use sandbox environment (default: true)
     */
    public function __construct(string $merchantId, string $merchantSecret, bool $isSandbox = true)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
        $this->isSandbox = $isSandbox;
    }
    
    /**
     * Get the merchant ID
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchantId;
    }
    
    /**
     * Get the merchant secret
     *
     * @return string
     */
    public function getMerchantSecret(): string
    {
        return $this->merchantSecret;
    }
    
    /**
     * Check if sandbox mode is enabled
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }
    
    /**
     * Get the checkout URL based on environment
     *
     * @return string
     */
    public function getCheckoutUrl(): string
    {
        return $this->isSandbox ? self::SANDBOX_URL : self::LIVE_URL;
    }
}
