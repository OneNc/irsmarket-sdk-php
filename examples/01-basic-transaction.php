<?php

/**
 * Example 1: Basic Transaction
 * 
 * Contoh dasar melakukan transaksi pulsa/kuota
 */

require __DIR__ . '/../vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

// Inisialisasi client
$client = new Client('your_api_key_here', 'your_api_secret_here');

try {
    // Melakukan transaksi pulsa Telkomsel 5000
    $response = $client->transaction(
        productCode: 'TSEL_5000',
        trxId: 'TRX_001_' . date('YmdHis'),
        customerNo: '081234567890'
    );

    // Cek hasil transaksi
    echo "Status: " . ($response->isSuccess() ? "SUCCESS" : "FAILED") . "\n";
    echo "Code: " . $response->getCode() . "\n";
    echo "Message: " . $response->getMessage() . "\n";
    echo "Reference: " . $response->getReff() . "\n";
    echo "Destination: " . $response->getDestination() . "\n";
} catch (IRSMarketException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getResponseCode() . "\n";
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
}
