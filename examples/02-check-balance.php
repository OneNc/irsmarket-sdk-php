<?php

/**
 * Example 2: Check Balance
 * 
 * Contoh mengecek saldo akun
 */

require __DIR__ . '/../vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

// Inisialisasi client
$client = new Client('your_api_key_here', 'your_api_secret_here');

try {
    // Mengecek saldo
    $response = $client->balance();

    if ($response->isSuccess()) {
        $data = $response->getData();

        echo "Member Name: " . $data['membername'] . "\n";
        echo "Balance: " . number_format($data['balance']) . "\n";
    } else {
        echo "Gagal mendapatkan saldo\n";
        echo "Message: " . $response->getMessage() . "\n";
    }
} catch (IRSMarketException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
}
