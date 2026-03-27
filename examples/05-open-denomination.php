<?php

/**
 * Example 5: Open Denomination Transaction
 * 
 * Contoh transaksi dengan nominal custom/open denomination
 * Nominal harus antara 10.000 - 500.000
 */

require __DIR__ . '/../vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

// Inisialisasi client
$client = new Client('your_api_key_here', 'your_api_secret_here');

try {
    // Contoh: Top-up E-Money dengan nominal custom
    $amount = 50000;  // 50 ribu rupiah

    if ($amount < 10000 || $amount > 500000) {
        echo "❌ Nominal harus antara 10.000 - 500.000\n";
        exit(1);
    }

    $response = $client->transaction(
        productCode: 'FLAZZ_CUSTOM',  // Product untuk open denomination
        trxId: 'TRX_TOPUP_' . time(),
        customerNo: '081234567890',
        amount: $amount  // Nominal custom
    );

    if ($response->isSuccess()) {
        echo "✅ Top-up berhasil!\n";
        echo "Amount: Rp " . number_format($amount) . "\n";
        echo "Reference: " . $response->getReff() . "\n";
        echo "Destination: " . $response->getDestination() . "\n";
    } else {
        echo "❌ Top-up gagal\n";
        echo "Code: " . $response->getCode() . "\n";
        echo "Message: " . $response->getMessage() . "\n";
    }
} catch (IRSMarketException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
}
