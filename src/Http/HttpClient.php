<?php

namespace IRSMarket\API\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use IRSMarket\API\Config;
use IRSMarket\API\Exception\IRSMarketException;

/**
 * HTTP Client wrapper for API requests
 */
class HttpClient
{
    /**
     * Guzzle client
     *
     * @var GuzzleClient
     */
    private $client;

    /**
     * API configuration
     *
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new GuzzleClient([
            'timeout' => $config->getTimeout(),
        ]);
    }

    /**
     * Send POST request
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws IRSMarketException
     */
    public function post(string $endpoint, array $data): array
    {
        try {
            $url = Config::getBaseUrl() . '/' . ltrim($endpoint, '/');

            $response = $this->client->post($url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new IRSMarketException('Invalid JSON response from API');
            }

            return $decoded;
        } catch (IRSMarketException $e) {
            // Re-throw IRSMarketException as-is
            throw $e;
        } catch (GuzzleException $e) {
            throw new IRSMarketException(
                'HTTP request failed: ' . $e->getMessage(),
                0,
                null,
                [],
                $e
            );
        } catch (\Exception $e) {
            throw new IRSMarketException(
                'Unexpected error: ' . $e->getMessage(),
                0,
                null,
                [],
                $e
            );
        }
    }

    /**
     * Send GET request
     *
     * @param string $endpoint
     * @param array $query
     * @return array
     * @throws IRSMarketException
     */
    public function get(string $endpoint, array $query = []): array
    {
        try {
            $url = Config::getBaseUrl() . '/' . ltrim($endpoint, '/');

            $response = $this->client->get($url, [
                'query' => $query,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new IRSMarketException('Invalid JSON response from API');
            }

            return $decoded;
        } catch (IRSMarketException $e) {
            // Re-throw IRSMarketException as-is
            throw $e;
        } catch (GuzzleException $e) {
            throw new IRSMarketException(
                'HTTP request failed: ' . $e->getMessage(),
                0,
                null,
                [],
                $e
            );
        } catch (\Exception $e) {
            throw new IRSMarketException(
                'Unexpected error: ' . $e->getMessage(),
                0,
                null,
                [],
                $e
            );
        }
    }
}
