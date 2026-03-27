<?php

namespace IRSMarket\API\Request;

/**
 * Balance request builder
 */
class BalanceRequest
{
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

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Build request data
     */
    public function buildData(): array
    {
        return [
            'apikey' => $this->apiKey,
            'apisecret' => $this->apiSecret,
        ];
    }
}
