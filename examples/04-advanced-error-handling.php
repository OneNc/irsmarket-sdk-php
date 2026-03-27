<?php

/**
 * Example 4: Advanced Error Handling
 * 
 * Contoh handling error dan exception secara terperinci
 */

require __DIR__ . '/../vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;
use IRSMarket\API\Config;

// Inisialisasi client
$client = new Client('your_api_key_here', 'your_api_secret_here');

try {
    // Transaksi
    $response = $client->transaction(
        productCode: 'TSEL_5000',
        trxId: 'TRX_003_' . date('YmdHis'),
        customerNo: '081234567890',
        maxPrice: 5500
    );

    // Cek status dengan detail
    if ($response->isSuccess()) {
        echo "✅ TRANSAKSI BERHASIL\n";
        echo "Reference: " . $response->getReff() . "\n";
        echo "Destination: " . $response->getDestination() . "\n";
        echo "Product: " . $response->getProductCode() . "\n";
    } elseif ($response->isPending()) {
        echo "⏳ TRANSAKSI PENDING\n";
        echo "Transaksi sedang diproses oleh sistem\n";
        echo "Reference: " . $response->getReff() . "\n";
        echo "Silakan cek status transaksi nanti\n";
    } else {
        echo "❌ TRANSAKSI GAGAL\n";
        echo "Code: " . $response->getCode() . "\n";
        echo "Message: " . $response->getMessage() . "\n";
        echo "Description: " . $response->getCodeDescription() . "\n";

        // Detail error berdasarkan code
        $code = $response->getCode();

        switch ($code) {
            case '11':
                echo "→ Masalah: API Key tidak valid\n";
                break;
            case '12':
                echo "→ Masalah: API Secret tidak valid\n";
                break;
            case '13':
                echo "→ Masalah: IP Address belum di-whitelist\n";
                break;
            case '61':
                echo "→ Masalah: Saldo tidak mencukupi\n";
                echo "→ Solusi: Silakan tambah deposit akun Anda\n";
                break;
            case '62':
                echo "→ Masalah: ID transaksi sudah pernah digunakan\n";
                echo "→ Solusi: Gunakan ID transaksi yang berbeda\n";
                break;
            case '67':
                echo "→ Masalah: Harga melebihi batas maksimum\n";
                break;
            case '71':
                echo "→ Masalah: Nominal tidak valid\n";
                break;
            case '72':
                echo "→ Masalah: Signature MD5 tidak valid\n";
                break;
            default:
                echo "→ Silakan hubungi support untuk penjelasan lebih lanjut\n";
        }
    }
} catch (IRSMarketException $e) {
    echo "❌ API ERROR\n";
    echo "Message: " . $e->getMessage() . "\n";

    if ($e->getResponseCode()) {
        echo "Response Code: " . $e->getResponseCode() . "\n";
        echo "Code Description: " . Config::getResponseCodeMessage($e->getResponseCode()) . "\n";
    }

    // Tampilkan raw response jika ada
    $responseData = $e->getResponseData();
    if (!empty($responseData)) {
        echo "\nFull Response:\n";
        var_dump($responseData);
    }
} catch (Exception $e) {
    echo "❌ UNEXPECTED ERROR\n";
    echo "Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
}
