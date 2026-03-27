<?php

namespace IRSMarket\API\Response;

use IRSMarket\API\Config;

/**
 * API Response handler
 */
class Response
{
    /**
     * Raw response data from API
     *
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Check if response is successful
     * Success only if rc code is '00'
     */
    public function isSuccess(): bool
    {
        return $this->getCode() === '00';
    }

    /**
     * Get response code
     */
    public function getCode(): string
    {
        return (string) ($this->data['rc'] ?? '');
    }

    /**
     * Get response code message
     */
    public function getMessage(): string
    {
        return (string) ($this->data['msg'] ?? '');
    }

    /**
     * Get response code description (human readable)
     */
    public function getCodeDescription(): string
    {
        return Config::getResponseCodeMessage($this->getCode());
    }

    /**
     * Get reference ID (reff)
     */
    public function getReff(): string
    {
        return (string) ($this->data['reff'] ?? '');
    }

    /**
     * Get destination (customer number)
     */
    public function getDestination(): string
    {
        return (string) ($this->data['destination'] ?? '');
    }

    /**
     * Get product code
     */
    public function getProductCode(): string
    {
        return (string) ($this->data['productcode'] ?? '');
    }

    /**
     * Get response data
     */
    public function getData(): array
    {
        return (array) ($this->data['data'] ?? []);
    }

    /**
     * Get raw response
     */
    public function getRawResponse(): array
    {
        return $this->data;
    }

    /**
     * Check if transaction is pending
     * Returns true if rc code is '68'
     */
    public function isPending(): bool
    {
        return $this->getCode() === '68';
    }

    /**
     * Check if transaction failed
     * Failed if not success (rc != '00') and not pending (rc != '68')
     */
    public function isFailed(): bool
    {
        $code = $this->getCode();
        return $code !== '00' && $code !== '68';
    }

    /**
     * Magic method to access array values
     */
    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }
}
