<?php

namespace IRSMarket\API\Exception;

use Exception;
use Throwable;

/**
 * Base exception class for IRSMarket API
 */
class IRSMarketException extends Exception
{
    /**
     * Response code from API
     *
     * @var string|null
     */
    protected $responseCode;

    /**
     * API response data
     *
     * @var array
     */
    protected $responseData;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?string $responseCode = null,
        array $responseData = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->responseCode = $responseCode;
        $this->responseData = $responseData;
    }

    /**
     * Get response code from API
     */
    public function getResponseCode(): ?string
    {
        return $this->responseCode;
    }

    /**
     * Get response data from API
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }
}
