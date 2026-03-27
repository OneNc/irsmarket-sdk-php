# IRSMarket API PHP Client Library

Library Composer untuk mengakses API IRSMarket. Library ini memudahkan integrasi sistem Anda dengan IRSMarket untuk melakukan transaksi pembelian produk digital seperti pulsa, kuota, voucher game, token listrik, dan layanan PPOB lainnya.

## 📦 Instalasi

```bash
composer require irsmarket/api-client
```

## 🚀 Quick Start

### Konfigurasi Dasar

```php
<?php
use IRSMarket\API\Client;

// Inisialisasi client dengan API key dan secret
$client = new Client('your_api_key', 'your_api_secret');
```

### Melakukan Transaksi

```php
<?php
try {
    // Transaksi pulsa Telkomsel 5000
    $response = $client->transaction(
        productCode: 'TSEL_5000',
        trxId: 'TRX_001_' . date('YmdHis'),
        customerNo: '081234567890',
        maxPrice: 5500  // optional
    );

    if ($response->isSuccess()) {
        echo "Transaksi berhasil!";
        echo "Reference: " . $response->getReff();
        echo "Destinasi: " . $response->getDestination();
    } elseif ($response->isPending()) {
        echo "Transaksi sedang diproses...";
    } else {
        echo "Transaksi gagal: " . $response->getMessage();
    }
} catch (\IRSMarket\API\Exception\IRSMarketException $e) {
    echo "Error: " . $e->getMessage();
    echo "Response Code: " . $e->getResponseCode();
}
?>
```

### Cek Saldo

```php
<?php
try {
    $response = $client->balance();
    
    if ($response->isSuccess()) {
        $data = $response->getData();
        echo "Member: " . $data['membername'];
        echo "Saldo: " . $data['balance'];
    }
} catch (\IRSMarket\API\Exception\IRSMarketException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

## 📋 API Reference

### Client Methods

#### `transaction()`

Mengirim transaksi menggunakan method POST.

**Parameters:**
- `productCode` (string): Kode produk (e.g., TSEL_5000, XL_10000)
- `trxId` (string): ID transaksi unik dari sistem Anda
- `customerNo` (string): Nomor tujuan (HP/Meter/ID Pelanggan)
- `maxPrice` (int, optional): Harga maksimal yang bersedia dibayar
- `amount` (int, optional): Nominal untuk open denomination (10.000-500.000)
- `useSignature` (bool, optional): Gunakan MD5 signature alih-alih API secret

**Returns:** `Response` object

**Example:**
```php
$response = $client->transaction(
    'TSEL_5000',
    'TRX_001_20250104_001',
    '081234567890',
    maxPrice: 5500,
    useSignature: false
);
```

#### `transactionGet()`

Mengirim transaksi menggunakan method GET.

**Parameters:** Sama dengan `transaction()`

**Returns:** `Response` object

**Example:**
```php
$response = $client->transactionGet(
    'TSEL_5000',
    'TRX_001_20250104_001',
    '081234567890'
);
```

#### `balance()`

Mengecek saldo akun.

**Returns:** `Response` object

**Example:**
```php
$response = $client->balance();
$balance = $response->getData()['balance'];
```

### Response Object

Semua method mengembalikan objek `Response` dengan method-method berikut:

```php
// Cek status
$response->isSuccess();           // true jika sukses
$response->isPending();           // true jika sedang diproses
$response->isFailed();            // true jika gagal

// Ambil data
$response->getCode();             // Kode response (00, 68, 11, dll)
$response->getMessage();          // Pesan dari API
$response->getCodeDescription();  // Deskripsi kode dalam bahasa Indonesia
$response->getReff();             // Reference/Reff ID
$response->getDestination();      // Nomor tujuan
$response->getProductCode();      // Kode produk
$response->getData();             // Array data tambahan
$response->getRawResponse();      // Raw response dari API
```

### Exception Handling

```php
try {
    $response = $client->transaction(...);
} catch (\IRSMarket\API\Exception\IRSMarketException $e) {
    echo $e->getMessage();              // Error message
    echo $e->getResponseCode();         // API response code
    var_dump($e->getResponseData());    // Full response data
}
```

## 🔐 Authentication Methods

### Metode 1: Menggunakan API Secret

```php
$response = $client->transaction(
    'TSEL_5000',
    'TRX_001',
    '081234567890',
    useSignature: false  // Default
);
```

### Metode 2: Menggunakan MD5 Signature

```php
$response = $client->transaction(
    'TSEL_5000',
    'TRX_001',
    '081234567890',
    useSignature: true   // Gunakan signature
);

// Signature dihitung sebagai: md5(apikey + apisecret + trxid)
```

## 📊 Response Codes

| Code | Meaning | Deskripsi |
|------|---------|-----------|
| 00 | Success | Transaksi berhasil |
| 68 | Pending | Transaksi sedang diproses |
| 11 | Invalid API Key | API Key tidak valid |
| 12 | Invalid API Secret | API Secret tidak valid |
| 13 | Invalid IP Address | IP Address tidak valid atau belum whitelist |
| 40 | Product Not Found | Produk tidak ditemukan |
| 41 | Product Not Active | Produk tidak aktif |
| 42 | Mapping Product Not Found | Mapping produk tidak ditemukan |
| 43 | Product Supplier Not Found | Supplier produk tidak ditemukan |
| 61 | Insufficient Balance | Saldo tidak mencukupi |
| 62 | Transaction Already Exists | ID transaksi sudah ada |
| 63 | Supplier Not Active | Supplier/toko sedang tutup |
| 64 | Transaction Processing Error | Terjadi kesalahan proses |
| 66 | Product Supplier Not Active | Supplier produk tidak aktif |
| 67 | Max Price Exceeded | Harga melebihi maksimum |
| 68 | Member Not Active | Member tidak aktif |
| 69 | No Seller | Tidak ada penjual untuk produk ini |
| 70 | No Seller in Region | Tidak ada penjual di wilayah Anda |
| 71 | Invalid Amount | Nominal tidak valid |
| 72 | Invalid Signature | Signature tidak valid |

## 💡 Contoh Penggunaan Lengkap

```php
<?php
use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

require 'vendor/autoload.php';

// Inisialisasi
$client = new Client('your_api_key', 'your_api_secret', timeout: 30);

try {
    // Cek saldo terlebih dahulu
    echo "=== CEK SALDO ===\n";
    $balanceResponse = $client->balance();
    
    if ($balanceResponse->isSuccess()) {
        $balanceData = $balanceResponse->getData();
        $currentBalance = $balanceData['balance'] ?? 0;
        echo "Saldo Saat Ini: " . number_format($currentBalance) . "\n";
    } else {
        echo "Gagal mengambil saldo\n";
        exit(1);
    }

    // Lakukan transaksi
    echo "\n=== TRANSAKSI PULSA ===\n";
    $trxResponse = $client->transaction(
        productCode: 'TSEL_5000',
        trxId: 'TRX_' . uniqid(),
        customerNo: '081234567890',
        maxPrice: 5500
    );

    if ($trxResponse->isSuccess()) {
        echo "✅ Transaksi berhasil!\n";
        echo "Reference: " . $trxResponse->getReff() . "\n";
        echo "Destinasi: " . $trxResponse->getDestination() . "\n";
        echo "Produk: " . $trxResponse->getProductCode() . "\n";
    } elseif ($trxResponse->isPending()) {
        echo "⏳ Transaksi sedang diproses...\n";
        echo "Reference: " . $trxResponse->getReff() . "\n";
    } else {
        echo "❌ Transaksi gagal\n";
        echo "Kode: " . $trxResponse->getCode() . "\n";
        echo "Pesan: " . $trxResponse->getMessage() . "\n";
        echo "Penjelasan: " . $trxResponse->getCodeDescription() . "\n";
    }

    // Cek saldo setelah transaksi
    echo "\n=== CEK SALDO AKHIR ===\n";
    $finalBalance = $client->balance();
    if ($finalBalance->isSuccess()) {
        $data = $finalBalance->getData();
        echo "Saldo Akhir: " . number_format($data['balance']) . "\n";
    }

} catch (IRSMarketException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Log response code jika ada
    if ($e->getResponseCode()) {
        echo "Response Code: " . $e->getResponseCode() . "\n";
    }
    
    // Log full response jika ada
    $responseData = $e->getResponseData();
    if (!empty($responseData)) {
        echo "Response Data:\n";
        var_dump($responseData);
    }
    
    exit(1);
} catch (Exception $e) {
    echo "Unexpected Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
```

## ⚙️ Konfigurasi

### Timeout Kustom

```php
$client = new Client(
    'your_api_key',
    'your_api_secret',
    timeout: 60  // 60 detik
);
```

### Akses Konfigurasi

```php
$config = $client->getConfig();

$apiKey = $config->getApiKey();
$apiSecret = $config->getApiSecret();
$timeout = $config->getTimeout();
$baseUrl = Config::getBaseUrl();
```

## 📖 Dokumentasi API Lengkap

Untuk dokumentasi API lengkap, kunjungi: https://irsmarket.com/integrasi

## ⚠️ Hal-hal Penting

1. **IP Whitelist**: Pastikan IP server Anda sudah di-whitelist di dashboard IRSMarket
2. **API Key & Secret**: Simpan credentials dengan aman, jangan commit ke repository
3. **Transaction ID Unik**: Pastikan `trxId` unik untuk setiap transaksi
4. **Amount Validation**: Untuk open denomination, amount harus 10.000-500.000
5. **Error Handling**: Selalu tangani exceptions dengan baik untuk mendapatkan error details

## 🔧 Development

### Running Tests

```bash
composer test
```

### Code Style Check

```bash
composer phpcs
```

## 📄 Lisensi

MIT License - Silakan gunakan library ini sesuai kebutuhan Anda.

## 📞 Support

Untuk pertanyaan dan support:
- Email: api-support@aviana.co.id
- Telepon: (+62361) 232045
- Jam Operasional: Senin-Sabtu, 08:00-17:00 WIB

## Changelog

### Version 1.0.0
- Initial release
- Transaction (POST & GET methods)
- Balance checking
- Complete exception handling
- MD5 signature support
