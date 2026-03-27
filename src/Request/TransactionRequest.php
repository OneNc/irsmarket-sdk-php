<?php

namespace IRSMarket\API\Request;

/**
 * Transaction request builder
 */
class TransactionRequest
{
    /**
     * API Key
     *
     * @var string
     */
    private $apiKey;

    /**
     * API Secret or Signature
     *
     * @var string|null
     */
    private $apiSecret;

    /**
     * MD5 Signature (alternative to apiSecret)
     *
     * @var string|null
     */
    private $sign;

    /**
     * Product code
     *
     * @var string
     */
    private $productCode;

    /**
     * Transaction ID (unique from your system)
     *
     * @var string
     */
    private $trxId;

    /**
     * Customer/destination number
     *
     * @var string
     */
    private $customerNo;

    /**
     * Maximum price (optional)
     *
     * @var int|null
     */
    private $maxPrice;

    /**
     * Amount for open denomination (10000-500000)
     *
     * @var int|null
     */
    private $amount;

    /**
     * Whether to use GET method
     *
     * @var bool
     */
    private $useGetMethod = false;

    public function __construct(string $apiKey, string $productCode, string $trxId, string $customerNo)
    {
        $this->apiKey = $apiKey;
        $this->productCode = $productCode;
        $this->trxId = $trxId;
        $this->customerNo = $customerNo;
    }

    /**
     * Set API secret
     */
    public function setApiSecret(string $apiSecret): self
    {
        $this->apiSecret = $apiSecret;
        $this->sign = null;
        return $this;
    }

    /**
     * Set signature (MD5 hash)
     * md5(apikey + apisecret + trxid)
     */
    public function setSignature(string $sign): self
    {
        $this->sign = $sign;
        $this->apiSecret = null;
        return $this;
    }

    /**
     * Auto-generate signature from apiKey, apiSecret and trxId
     */
    public function generateSignature(string $apiSecret): self
    {
        $this->sign = md5($this->apiKey . $apiSecret . $this->trxId);
        $this->apiSecret = null;
        return $this;
    }

    /**
     * Set max price
     */
    public function setMaxPrice(int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * Set amount (for open denomination)
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Use GET method instead of POST
     */
    public function useGetMethod(): self
    {
        $this->useGetMethod = true;
        return $this;
    }

    /**
     * Check if using GET method
     */
    public function isGetMethod(): bool
    {
        return $this->useGetMethod;
    }

    /**
     * Build request data for POST
     */
    public function buildData(): array
    {
        $data = [
            'apikey' => $this->apiKey,
            'productcode' => $this->productCode,
            'trxid' => $this->trxId,
            'customerno' => $this->customerNo,
        ];

        if ($this->apiSecret !== null) {
            $data['apisecret'] = $this->apiSecret;
        } elseif ($this->sign !== null) {
            $data['sign'] = $this->sign;
        }

        if ($this->maxPrice !== null) {
            $data['maxprice'] = (string) $this->maxPrice;
        }

        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }

        return $data;
    }

    /**
     * Build query parameters for GET
     */
    public function buildQuery(): array
    {
        $query = [
            'apikey' => $this->apiKey,
            'productcode' => $this->productCode,
            'trxid' => $this->trxId,
            'customerno' => $this->customerNo,
        ];

        if ($this->apiSecret !== null) {
            $query['apisecret'] = $this->apiSecret;
        } elseif ($this->sign !== null) {
            $query['sign'] = $this->sign;
        }

        if ($this->maxPrice !== null) {
            $query['maxprice'] = (string) $this->maxPrice;
        }

        if ($this->amount !== null) {
            $query['amount'] = (string) $this->amount;
        }

        return $query;
    }

    /**
     * Get transaction ID
     */
    public function getTrxId(): string
    {
        return $this->trxId;
    }
}
