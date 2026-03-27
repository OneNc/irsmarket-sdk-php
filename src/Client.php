<?php

namespace IRSMarket\API;

use IRSMarket\API\Exception\IRSMarketException;
use IRSMarket\API\Http\HttpClient;
use IRSMarket\API\Request\BalanceRequest;
use IRSMarket\API\Request\TransactionRequest;
use IRSMarket\API\Response\Response;

/**
 * Main IRSMarket API Client
 *
 * Usage:
 * $client = new Client('your_api_key', 'your_api_secret');
 * $response = $client->transaction('TSEL_5000', 'TRX_001', '081234567890');
 * $balance = $client->balance();
 */
class Client
{
    /**
     * API configuration
     *
     * @var Config
     */
    private $config;

    /**
     * HTTP client
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param int $timeout Request timeout in seconds
     */
    public function __construct(string $apiKey, string $apiSecret, int $timeout = 30)
    {
        $this->config = new Config($apiKey, $apiSecret, $timeout);
        $this->httpClient = new HttpClient($this->config);
    }

    /**
     * Send transaction request
     *
     * @param string $productCode Product code (e.g., TSEL_5000)
     * @param string $trxId Transaction ID from your system
     * @param string $customerNo Customer number (phone/meter/ID)
     * @param int|null $maxPrice Maximum price (optional)
     * @param int|null $amount Amount for open denomination (optional)
     * @param bool $useSignature Use MD5 signature instead of API secret
     * @return Response
     * @throws IRSMarketException
     */
    public function transaction(
        string $productCode,
        string $trxId,
        string $customerNo,
        int $maxPrice = null,
        int $amount = null,
        bool $useSignature = false
    ): Response {
        $request = new TransactionRequest(
            $this->config->getApiKey(),
            $productCode,
            $trxId,
            $customerNo
        );

        if ($useSignature) {
            $request->generateSignature($this->config->getApiSecret());
        } else {
            $request->setApiSecret($this->config->getApiSecret());
        }

        if ($maxPrice !== null) {
            $request->setMaxPrice($maxPrice);
        }

        if ($amount !== null) {
            $request->setAmount($amount);
        }

        try {
            $data = $this->httpClient->post('transaction', $request->buildData());
            $response = new Response($data);

            if ($response->isFailed()) {
                throw new IRSMarketException(
                    'Transaction failed: ' . $response->getMessage(),
                    0,
                    $response->getCode(),
                    $response->getRawResponse()
                );
            }

            return $response;
        } catch (IRSMarketException $e) {
            throw $e;
        }
    }

    /**
     * Send transaction request using GET method
     *
     * @param string $productCode Product code
     * @param string $trxId Transaction ID
     * @param string $customerNo Customer number
     * @param int|null $maxPrice Maximum price (optional)
     * @param int|null $amount Amount (optional)
     * @param bool $useSignature Use MD5 signature instead of API secret
     * @return Response
     * @throws IRSMarketException
     */
    public function transactionGet(
        string $productCode,
        string $trxId,
        string $customerNo,
        int $maxPrice = null,
        int $amount = null,
        bool $useSignature = false
    ): Response {
        $request = new TransactionRequest(
            $this->config->getApiKey(),
            $productCode,
            $trxId,
            $customerNo
        );

        if ($useSignature) {
            $request->generateSignature($this->config->getApiSecret());
        } else {
            $request->setApiSecret($this->config->getApiSecret());
        }

        if ($maxPrice !== null) {
            $request->setMaxPrice($maxPrice);
        }

        if ($amount !== null) {
            $request->setAmount($amount);
        }

        try {
            $data = $this->httpClient->get('transaction', $request->buildQuery());
            $response = new Response($data);

            if ($response->isFailed()) {
                throw new IRSMarketException(
                    'Transaction failed: ' . $response->getMessage(),
                    0,
                    $response->getCode(),
                    $response->getRawResponse()
                );
            }

            return $response;
        } catch (IRSMarketException $e) {
            throw $e;
        }
    }

    /**
     * Check account balance
     *
     * @return Response
     * @throws IRSMarketException
     */
    public function balance(): Response
    {
        $request = new BalanceRequest(
            $this->config->getApiKey(),
            $this->config->getApiSecret()
        );

        try {
            $data = $this->httpClient->post('balance', $request->buildData());
            return new Response($data);
        } catch (IRSMarketException $e) {
            throw $e;
        }
    }

    /**
     * Get API configuration
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
}
