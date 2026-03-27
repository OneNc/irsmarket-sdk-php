<?php

namespace IRSMarket\API;

/**
 * Configuration class for IRSMarket API
 */
class Config
{
    const BASE_URL = 'https://api.irsmarket.com/v1';

    /**
     * API Key
     *
     * @var string
     */
    private $apiKey;

    /**
     * API Secret
     *
     * @var string
     */
    private $apiSecret;

    /**
     * Request timeout in seconds
     *
     * @var int
     */
    private $timeout = 30;

    /**
     * Response codes mapping
     *
     * @var array
     */
    private static $responseCodes = [
        '00' => 'Success',
        '68' => 'Pending (under process)',
        '11' => 'Invalid API Key',
        '12' => 'Invalid API Secret',
        '13' => 'Invalid IP Address',
        '40' => 'Product Not Found',
        '41' => 'Product Not Active',
        '42' => 'Mapping Product Not Found',
        '43' => 'Product Supplier Not Found',
        '61' => 'Insufficient Balance',
        '62' => 'Transaction Already Exists',
        '63' => 'Supplier Not Active',
        '64' => 'Transaction Processing Error',
        '66' => 'Product Supplier Not Active',
        '67' => 'Max Price Exceeded',
        '68' => 'Member Not Active',
        '69' => 'No Seller Selling This Product',
        '70' => 'No Seller Selling This Product In Region',
        '71' => 'Invalid Amount',
        '72' => 'Invalid Signature',
    ];

    public function __construct(string $apiKey, string $apiSecret, int $timeout = 30)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->timeout = $timeout;
    }

    /**
     * Get API Key
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Get API Secret
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * Get request timeout
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Get base URL
     */
    public static function getBaseUrl(): string
    {
        return self::BASE_URL;
    }

    /**
     * Get response code description
     */
    public static function getResponseCodeMessage(string $code): string
    {
        return self::$responseCodes[$code] ?? 'Unknown response code';
    }

    /**
     * Get all response codes
     */
    public static function getResponseCodes(): array
    {
        return self::$responseCodes;
    }
}
