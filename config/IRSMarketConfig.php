<?php

/**
 * Configuration Helper
 * 
 * Helper class untuk mengelola konfigurasi credentials
 * Impor ke file-file yang membutuhkan akses ke IRSMarket API
 */

class IRSMarketConfig
{
    /**
     * Load configuration dari environment variables
     * Set via .env atau $_ENV
     */
    public static function fromEnv(): \IRSMarket\API\Client
    {
        $apiKey = getenv('IRSMARKET_API_KEY') ?: $_ENV['IRSMARKET_API_KEY'] ?? null;
        $apiSecret = getenv('IRSMARKET_API_SECRET') ?: $_ENV['IRSMARKET_API_SECRET'] ?? null;
        $timeout = (int) (getenv('IRSMARKET_TIMEOUT') ?: $_ENV['IRSMARKET_TIMEOUT'] ?? 30);

        if (!$apiKey || !$apiSecret) {
            throw new Exception('IRSMARKET_API_KEY dan IRSMARKET_API_SECRET harus di-set di .env atau environment');
        }

        return new \IRSMarket\API\Client($apiKey, $apiSecret, $timeout);
    }

    /**
     * Load configuration dari array/config file
     */
    public static function fromArray(array $config): \IRSMarket\API\Client
    {
        $apiKey = $config['api_key'] ?? null;
        $apiSecret = $config['api_secret'] ?? null;
        $timeout = (int) ($config['timeout'] ?? 30);

        if (!$apiKey || !$apiSecret) {
            throw new Exception('api_key dan api_secret harus disediakan');
        }

        return new \IRSMarket\API\Client($apiKey, $apiSecret, $timeout);
    }

    /**
     * Load configuration dari file .env
     */
    public static function loadEnvFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception('.env file not found: ' . $filePath);
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse key=value
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, '\'"');

                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Contoh penggunaan:
/*
// 1. Load dari environment
$client = IRSMarketConfig::fromEnv();

// 2. Load dari array
$client = IRSMarketConfig::fromArray([
    'api_key' => 'your_api_key',
    'api_secret' => 'your_api_secret',
    'timeout' => 30
]);

// 3. Load dari .env file
IRSMarketConfig::loadEnvFile(__DIR__ . '/.env');
$client = IRSMarketConfig::fromEnv();
*/
