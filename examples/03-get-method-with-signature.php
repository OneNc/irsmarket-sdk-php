<?php

/**
 * Example 3: Transaction with GET method and MD5 Signature
 * 
 * Contoh transaksi menggunakan GET method dengan MD5 signature
 */

require __DIR__ . '/../vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

// Inisialisasi client
$client = new Client('your_api_key_here', 'your_api_secret_here');

try {
    // Transaksi menggunakan GET method dengan signature
    $response = $client->transactionGet(
        productCode: 'TSEL_5000',
        trxId: 'TRX_002_' . date('YmdHis'),
        customerNo: '081234567890',
        maxPrice: 5500,
        useSignature: true  // Gunakan MD5 signature
    );

    echo "Transaction Type: GET Method\n";
    echo "Using Signature: Yes\n";
    echo "Status: " . ($response->isSuccess() ? "SUCCESS" : "FAILED") . "\n";
    echo "Code: " . $response->getCode() . "\n";
    echo "Message: " . $response->getMessage() . "\n";
    echo "Reference: " . $response->getReff() . "\n";
} catch (IRSMarketException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Response Code: " . $e->getResponseCode() . "\n";
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
}
