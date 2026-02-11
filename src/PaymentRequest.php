<?php

namespace Payhere;

use Payhere\Exceptions\InvalidAmountException;
use Payhere\Exceptions\InvalidCurrencyException;
use Payhere\Exceptions\InvalidPaymentDataException;
use Payhere\Exceptions\MissingRequiredFieldException;

/**
 * Payment Request Builder with fluent interface
 */
class PaymentRequest
{
    private Config $config;
    private array $data = [];
    
    /**
     * Required fields for payment request
     */
    private const REQUIRED_FIELDS = [
        'order_id',
        'amount',
        'currency',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'country'
    ];
    
    /**
     * Supported currencies
     */
    private const SUPPORTED_CURRENCIES = ['LKR', 'USD', 'GBP', 'EUR', 'AUD'];
    
    /**
     * Create a new PaymentRequest instance
     *
     * @param Config $config Configuration instance
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data['merchant_id'] = $config->getMerchantId();
        $this->data['currency'] = 'LKR'; // Default currency
    }
    
    /**
     * Set the order ID
     *
     * @param string $orderId Unique order identifier
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setOrderId(string $orderId): self
    {
        $orderId = trim($orderId);
        
        if (empty($orderId)) {
            throw new InvalidPaymentDataException(
                'Order ID cannot be empty',
                ['order_id' => $orderId]
            );
        }
        
        if (strlen($orderId) > 50) {
            throw new InvalidPaymentDataException(
                'Order ID cannot exceed 50 characters',
                ['order_id' => $orderId, 'length' => strlen($orderId)]
            );
        }
        
        // Check for invalid characters
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $orderId)) {
            throw new InvalidPaymentDataException(
                'Order ID can only contain alphanumeric characters, hyphens, and underscores',
                ['order_id' => $orderId]
            );
        }
        
        $this->data['order_id'] = $orderId;
        return $this;
    }
    
    /**
     * Set the payment amount
     *
     * @param float $amount Payment amount
     * @return self
     * @throws InvalidAmountException
     */
    public function setAmount(float $amount): self
    {
        if ($amount <= 0) {
            throw new InvalidAmountException(
                'Amount must be greater than 0',
                $amount
            );
        }
        
        if ($amount > 999999.99) {
            throw new InvalidAmountException(
                'Amount exceeds maximum allowed value of 999,999.99',
                $amount
            );
        }
        
        // Format to 2 decimal places
        $this->data['amount'] = number_format($amount, 2, '.', '');
        return $this;
    }
    
    /**
     * Set the currency
     *
     * @param string $currency Currency code (LKR, USD, GBP, EUR, AUD)
     * @return self
     * @throws InvalidCurrencyException
     */
    public function setCurrency(string $currency): self
    {
        $currency = strtoupper(trim($currency));
        
        if (!in_array($currency, self::SUPPORTED_CURRENCIES, true)) {
            throw new InvalidCurrencyException($currency);
        }
        
        $this->data['currency'] = $currency;
        return $this;
    }
    
    /**
     * Set item details
     *
     * @param string $itemName Item name/description
     * @param int $itemNumber Number of items (default: 1)
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setItems(string $itemName, int $itemNumber = 1): self
    {
        $itemName = trim($itemName);
        
        if (empty($itemName)) {
            throw new InvalidPaymentDataException(
                'Item name cannot be empty'
            );
        }
        
        if (strlen($itemName) > 100) {
            throw new InvalidPaymentDataException(
                'Item name cannot exceed 100 characters',
                ['item_name' => $itemName, 'length' => strlen($itemName)]
            );
        }
        
        if ($itemNumber < 1) {
            throw new InvalidPaymentDataException(
                'Item number must be at least 1',
                ['item_number' => $itemNumber]
            );
        }
        
        if ($itemNumber > 9999) {
            throw new InvalidPaymentDataException(
                'Item number cannot exceed 9999',
                ['item_number' => $itemNumber]
            );
        }
        
        $this->data['items'] = $this->sanitize($itemName);
        $this->data['item_number'] = $itemNumber;
        return $this;
    }
    
    /**
     * Set customer information
     *
     * @param string $firstName Customer first name
     * @param string $lastName Customer last name
     * @param string $email Customer email address
     * @param string $phone Customer phone number
     * @param string $address Customer address
     * @param string $city Customer city
     * @param string $country Customer country
     * @return self
     * @throws InvalidPaymentDataException
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
        // Validate first name
        $firstName = trim($firstName);
        if (empty($firstName)) {
            throw new InvalidPaymentDataException('First name cannot be empty');
        }
        if (strlen($firstName) > 50) {
            throw new InvalidPaymentDataException(
                'First name cannot exceed 50 characters',
                ['first_name' => $firstName, 'length' => strlen($firstName)]
            );
        }
        
        // Validate last name
        $lastName = trim($lastName);
        if (empty($lastName)) {
            throw new InvalidPaymentDataException('Last name cannot be empty');
        }
        if (strlen($lastName) > 50) {
            throw new InvalidPaymentDataException(
                'Last name cannot exceed 50 characters',
                ['last_name' => $lastName, 'length' => strlen($lastName)]
            );
        }
        
        // Validate email
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidPaymentDataException(
                'Invalid email address format',
                ['email' => $email]
            );
        }
        if (strlen($email) > 100) {
            throw new InvalidPaymentDataException(
                'Email address cannot exceed 100 characters',
                ['email' => $email, 'length' => strlen($email)]
            );
        }
        
        // Validate phone
        $phone = trim($phone);
        // Remove common separators for validation
        $phoneDigits = preg_replace('/[\s\-\(\)]/', '', $phone);
        if (strlen($phoneDigits) < 9 || strlen($phoneDigits) > 15) {
            throw new InvalidPaymentDataException(
                'Phone number must be between 9 and 15 digits',
                ['phone' => $phone, 'digits' => strlen($phoneDigits)]
            );
        }
        
        // Validate address
        $address = trim($address);
        if (empty($address)) {
            throw new InvalidPaymentDataException('Address cannot be empty');
        }
        if (strlen($address) > 200) {
            throw new InvalidPaymentDataException(
                'Address cannot exceed 200 characters',
                ['address' => $address, 'length' => strlen($address)]
            );
        }
        
        // Validate city
        $city = trim($city);
        if (empty($city)) {
            throw new InvalidPaymentDataException('City cannot be empty');
        }
        if (strlen($city) > 50) {
            throw new InvalidPaymentDataException(
                'City cannot exceed 50 characters',
                ['city' => $city, 'length' => strlen($city)]
            );
        }
        
        // Validate country
        $country = trim($country);
        if (empty($country)) {
            throw new InvalidPaymentDataException('Country cannot be empty');
        }
        if (strlen($country) > 50) {
            throw new InvalidPaymentDataException(
                'Country cannot exceed 50 characters',
                ['country' => $country, 'length' => strlen($country)]
            );
        }
        
        // Set sanitized values
        $this->data['first_name'] = $this->sanitize($firstName);
        $this->data['last_name'] = $this->sanitize($lastName);
        $this->data['email'] = $email;
        $this->data['phone'] = $phone;
        $this->data['address'] = $this->sanitize($address);
        $this->data['city'] = $this->sanitize($city);
        $this->data['country'] = $this->sanitize($country);
        
        return $this;
    }
    
    /**
     * Set return URL (customer redirect after payment)
     *
     * @param string $returnUrl URL to redirect customer after payment
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setReturnUrl(string $returnUrl): self
    {
        $returnUrl = trim($returnUrl);
        
        if (!filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidPaymentDataException(
                'Invalid return URL format',
                ['url' => $returnUrl]
            );
        }
        
        // Check for HTTPS in production
        if (!$this->config->isSandbox() && !str_starts_with($returnUrl, 'https://')) {
            throw new InvalidPaymentDataException(
                'Return URL must use HTTPS in production mode',
                ['url' => $returnUrl]
            );
        }
        
        $this->data['return_url'] = $returnUrl;
        return $this;
    }
    
    /**
     * Set cancel URL (customer redirect if payment is canceled)
     *
     * @param string $cancelUrl URL to redirect customer if payment is canceled
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setCancelUrl(string $cancelUrl): self
    {
        $cancelUrl = trim($cancelUrl);
        
        if (!filter_var($cancelUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidPaymentDataException(
                'Invalid cancel URL format',
                ['url' => $cancelUrl]
            );
        }
        
        // Check for HTTPS in production
        if (!$this->config->isSandbox() && !str_starts_with($cancelUrl, 'https://')) {
            throw new InvalidPaymentDataException(
                'Cancel URL must use HTTPS in production mode',
                ['url' => $cancelUrl]
            );
        }
        
        $this->data['cancel_url'] = $cancelUrl;
        return $this;
    }
    
    /**
     * Set notify URL (server-to-server callback)
     *
     * @param string $notifyUrl URL for PayHere to send payment notifications
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setNotifyUrl(string $notifyUrl): self
    {
        $notifyUrl = trim($notifyUrl);
        
        if (!filter_var($notifyUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidPaymentDataException(
                'Invalid notify URL format',
                ['url' => $notifyUrl]
            );
        }
        
        // Check for HTTPS in production
        if (!$this->config->isSandbox() && !str_starts_with($notifyUrl, 'https://')) {
            throw new InvalidPaymentDataException(
                'Notify URL must use HTTPS in production mode',
                ['url' => $notifyUrl]
            );
        }
        
        $this->data['notify_url'] = $notifyUrl;
        return $this;
    }
    
    /**
     * Set custom fields (optional metadata)
     *
     * @param string $custom1 Custom field 1
     * @param string|null $custom2 Custom field 2 (optional)
     * @return self
     * @throws InvalidPaymentDataException
     */
    public function setCustomFields(string $custom1, ?string $custom2 = null): self
    {
        $custom1 = trim($custom1);
        
        if (empty($custom1)) {
            throw new InvalidPaymentDataException('Custom field 1 cannot be empty');
        }
        
        if (strlen($custom1) > 100) {
            throw new InvalidPaymentDataException(
                'Custom field 1 cannot exceed 100 characters',
                ['custom_1' => $custom1, 'length' => strlen($custom1)]
            );
        }
        
        $this->data['custom_1'] = $this->sanitize($custom1);
        
        if ($custom2 !== null) {
            $custom2 = trim($custom2);
            
            if (strlen($custom2) > 100) {
                throw new InvalidPaymentDataException(
                    'Custom field 2 cannot exceed 100 characters',
                    ['custom_2' => $custom2, 'length' => strlen($custom2)]
                );
            }
            
            $this->data['custom_2'] = $this->sanitize($custom2);
        }
        
        return $this;
    }
    
    /**
     * Validate that all required fields are set
     *
     * @throws MissingRequiredFieldException
     */
    private function validateRequiredFields(): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
                throw new MissingRequiredFieldException(
                    $field,
                    "Required field '{$field}' is missing or empty"
                );
            }
        }
    }
    
    /**
     * Generate secure hash for payment request
     *
     * @return string MD5 hash
     */
    private function generateHash(): string
    {
        $merchantId = $this->config->getMerchantId();
        $merchantSecret = $this->config->getMerchantSecret();
        $orderId = $this->data['order_id'];
        $amount = $this->data['amount'];
        $currency = $this->data['currency'];
        
        $hashedSecret = strtoupper(md5($merchantSecret));
        $amountFormatted = number_format((float)$amount, 2, '.', '');
        
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
     * @return array Payment data with hash
     * @throws MissingRequiredFieldException
     */
    public function getData(): array
    {
        $this->validateRequiredFields();
        
        $data = $this->data;
        $data['hash'] = $this->generateHash();
        
        return $data;
    }
    
    /**
     * Generate HTML form for payment submission
     *
     * @param string $submitButtonText Text for submit button
     * @param array $buttonAttributes Additional button attributes
     * @return string HTML form
     * @throws MissingRequiredFieldException
     */
    public function generateForm(
        string $submitButtonText = 'Pay Now',
        array $buttonAttributes = []
    ): string {
        $data = $this->getData();
        $checkoutUrl = $this->config->getCheckoutUrl();
        
        $html = '<form method="post" action="' . htmlspecialchars($checkoutUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        
        foreach ($data as $key => $value) {
            $html .= sprintf(
                '    <input type="hidden" name="%s" value="%s">' . "\n",
                htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
            );
        }
        
        // Default button attributes
        $defaultAttributes = [
            'type' => 'submit',
            'class' => 'payhere-button'
        ];
        
        $attributes = array_merge($defaultAttributes, $buttonAttributes);
        
        $attrString = '';
        foreach ($attributes as $attr => $val) {
            $attrString .= sprintf(
                ' %s="%s"',
                htmlspecialchars($attr, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($val, ENT_QUOTES, 'UTF-8')
            );
        }
        
        $html .= sprintf(
            '    <button%s>%s</button>' . "\n",
            $attrString,
            htmlspecialchars($submitButtonText, ENT_QUOTES, 'UTF-8')
        );
        
        $html .= '</form>';
        
        return $html;
    }
    
    /**
     * Auto-redirect to PayHere payment gateway
     *
     * @throws MissingRequiredFieldException
     */
    public function redirect(): void
    {
        $data = $this->getData();
        $checkoutUrl = $this->config->getCheckoutUrl();
        
        echo '<!DOCTYPE html>';
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<form id="payhere_payment_form" method="post" action="' . htmlspecialchars($checkoutUrl, ENT_QUOTES, 'UTF-8') . '">';
        
        foreach ($data as $key => $value) {
            echo sprintf(
                '<input type="hidden" name="%s" value="%s">',
                htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
            );
        }
        
        echo '</form>';
        echo '<script>document.getElementById("payhere_payment_form").submit();</script>';
        echo '</body></html>';
        exit;
    }
    
    /**
     * Get payment summary (useful for displaying to user before payment)
     *
     * @return array Payment summary
     * @throws MissingRequiredFieldException
     */
    public function getSummary(): array
    {
        $this->validateRequiredFields();
        
        return [
            'order_id' => $this->data['order_id'],
            'amount' => $this->data['amount'],
            'currency' => $this->data['currency'],
            'items' => $this->data['items'] ?? null,
            'item_number' => $this->data['item_number'] ?? null,
            'customer' => [
                'name' => $this->data['first_name'] . ' ' . $this->data['last_name'],
                'email' => $this->data['email'],
                'phone' => $this->data['phone']
            ]
        ];
    }
    
    /**
     * Sanitize input string
     *
     * @param string $input Input string
     * @return string Sanitized string
     */
    private function sanitize(string $input): string
    {
        return htmlspecialchars(
            strip_tags(trim($input)),
            ENT_QUOTES,
            'UTF-8'
        );
    }
    
    /**
     * Reset payment data (useful for creating multiple payments)
     *
     * @return self
     */
    public function reset(): self
    {
        $this->data = [
            'merchant_id' => $this->config->getMerchantId(),
            'currency' => 'LKR'
        ];
        
        return $this;
    }
}