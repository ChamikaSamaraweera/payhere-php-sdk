<?php

namespace Payhere;

/**
 * Payment Request Builder
 */
class PaymentRequest
{
    private Config $config;
    private array $data = [];
    
    /**
     * Create a new PaymentRequest instance
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data['merchant_id'] = $config->getMerchantId();
    }
    
    /**
     * Set the order ID
     *
     * @param string $orderId
     * @return self
     */
    public function setOrderId(string $orderId): self
    {
        $this->data['order_id'] = $orderId;
        return $this;
    }
    
    /**
     * Set the payment amount
     *
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->data['amount'] = number_format($amount, 2, '.', '');
        return $this;
    }
    
    /**
     * Set the currency (default: LKR)
     *
     * @param string $currency
     * @return self
     */
    public function setCurrency(string $currency = 'LKR'): self
    {
        $this->data['currency'] = $currency;
        return $this;
    }
    
    /**
     * Set item details
     *
     * @param string $itemName
     * @param int $itemNumber
     * @return self
     */
    public function setItems(string $itemName, int $itemNumber = 1): self
    {
        $this->data['items'] = $itemName;
        $this->data['item_number'] = $itemNumber;
        return $this;
    }
    
    /**
     * Set customer information
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param string $address
     * @param string $city
     * @param string $country
     * @return self
     */
    public function setCustomer(
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $address,
        string $city,
        string $country
    ): self {
        $this->data['first_name'] = $firstName;
        $this->data['last_name'] = $lastName;
        $this->data['email'] = $email;
        $this->data['phone'] = $phone;
        $this->data['address'] = $address;
        $this->data['city'] = $city;
        $this->data['country'] = $country;
        return $this;
    }
    
    /**
     * Set return URL (where customer is redirected after payment)
     *
     * @param string $returnUrl
     * @return self
     */
    public function setReturnUrl(string $returnUrl): self
    {
        $this->data['return_url'] = $returnUrl;
        return $this;
    }
    
    /**
     * Set cancel URL (where customer is redirected if payment is cancelled)
     *
     * @param string $cancelUrl
     * @return self
     */
    public function setCancelUrl(string $cancelUrl): self
    {
        $this->data['cancel_url'] = $cancelUrl;
        return $this;
    }
    
    /**
     * Set notify URL (server callback URL for payment notifications)
     *
     * @param string $notifyUrl
     * @return self
     */
    public function setNotifyUrl(string $notifyUrl): self
    {
        $this->data['notify_url'] = $notifyUrl;
        return $this;
    }
    
    /**
     * Set custom fields (optional)
     *
     * @param string $custom1
     * @param string|null $custom2
     * @return self
     */
    public function setCustomFields(string $custom1, ?string $custom2 = null): self
    {
        $this->data['custom_1'] = $custom1;
        if ($custom2 !== null) {
            $this->data['custom_2'] = $custom2;
        }
        return $this;
    }
    
    /**
     * Generate hash for the payment request
     *
     * @return string
     * @throws \Exception
     */
    private function generateHash(): string
    {
        $requiredFields = ['order_id', 'amount', 'currency'];
        foreach ($requiredFields as $field) {
            if (!isset($this->data[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
        
        $merchantSecret = $this->config->getMerchantSecret();
        $merchantId = $this->config->getMerchantId();
        $orderId = $this->data['order_id'];
        $amount = $this->data['amount'];
        $currency = $this->data['currency'];
        
        $hashedSecret = strtoupper(md5($merchantSecret));
        $amountFormatted = number_format($amount, 2, '.', '');
        $hash = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $amountFormatted . 
                $currency . 
                $hashedSecret
            )
        );
        
        return $hash;
    }
    
    /**
     * Get all payment data including hash
     *
     * @return array
     * @throws \Exception
     */
    public function getData(): array
    {
        $this->data['hash'] = $this->generateHash();
        return $this->data;
    }
    
    /**
     * Generate HTML form for payment
     *
     * @param string $submitButtonText
     * @param array $formAttributes
     * @return string
     * @throws \Exception
     */
    public function generateForm(string $submitButtonText = 'Pay Now', array $formAttributes = []): string
    {
        $data = $this->getData();
        $checkoutUrl = $this->config->getCheckoutUrl();
        
        $defaultAttributes = [
            'method' => 'post',
            'action' => $checkoutUrl
        ];
        
        $attributes = array_merge($defaultAttributes, $formAttributes);
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= sprintf('%s="%s" ', htmlspecialchars($key), htmlspecialchars($value));
        }
        
        $html = "<form {$attributeString}>\n";
        
        foreach ($data as $key => $value) {
            $html .= sprintf(
                '    <input type="hidden" name="%s" value="%s">' . "\n",
                htmlspecialchars($key),
                htmlspecialchars($value)
            );
        }
        
        $html .= sprintf('    <button type="submit">%s</button>' . "\n", htmlspecialchars($submitButtonText));
        $html .= "</form>";
        
        return $html;
    }
    
    /**
     * Redirect to PayHere checkout
     *
     * @throws \Exception
     */
    public function redirect(): void
    {
        $data = $this->getData();
        $checkoutUrl = $this->config->getCheckoutUrl();
        
        echo '<html><body>';
        echo '<form id="payhere_form" method="post" action="' . htmlspecialchars($checkoutUrl) . '">';
        
        foreach ($data as $key => $value) {
            echo sprintf(
                '<input type="hidden" name="%s" value="%s">',
                htmlspecialchars($key),
                htmlspecialchars($value)
            );
        }
        
        echo '</form>';
        echo '<script>document.getElementById("payhere_form").submit();</script>';
        echo '</body></html>';
        exit;
    }
}
