# Installation & Setup Guide

## Prerequisites

- PHP 7.4 atau lebih tinggi
- Composer
- cURL extension (untuk HTTP requests)
- JSON extension
- API Key dan Secret dari IRSMarket (dapatkan dari dashboard)

## Step-by-Step Installation

### 1. Install via Composer

```bash
composer require onenc/irsmarket-sdk-php
```

### 2. Setup Credentials

#### Option A: Environment Variables (.env)

Copy file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit `.env` dan isi dengan credentials Anda:

```env
IRSMARKET_API_KEY=your_api_key_here
IRSMARKET_API_SECRET=your_api_secret_here
IRSMARKET_TIMEOUT=30
```

#### Option B: Direct Configuration

```php
<?php
use IRSMarket\API\Client;

$client = new Client(
    apiKey: 'your_api_key_here',
    apiSecret: 'your_api_secret_here',
    timeout: 30
);
?>
```

### 3. Whitelist IP Address

**Penting!** Anda harus whitelist IP server Anda di dashboard IRSMarket:

1. Login ke dashboard IRSMarket
2. Pergi ke Settings > IP Whitelist
3. Tambahkan IP publik server Anda
4. Simpan

Jika IP belum di-whitelist, akan mendapat error code `13: Invalid IP Address`.

### 4. Test Connection

```php
<?php
use IRSMarket\API\Client;

$client = new Client('your_api_key', 'your_api_secret');

try {
    $response = $client->balance();
    
    if ($response->isSuccess()) {
        echo "✅ API Connection Successful!\n";
        $data = $response->getData();
        echo "Member: " . $data['membername'] . "\n";
        echo "Balance: " . $data['balance'] . "\n";
    } else {
        echo "❌ Connection failed\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### 5. Implementasi di Project

```php
<?php
require 'vendor/autoload.php';

use IRSMarket\API\Client;
use IRSMarket\API\Exception\IRSMarketException;

// Load config dari environment
$client = new Client(
    getenv('IRSMARKET_API_KEY'),
    getenv('IRSMARKET_API_SECRET')
);

try {
    // Lakukan transaksi
    $response = $client->transaction(
        'TSEL_5000',
        'TRX_' . uniqid(),
        '081234567890'
    );
    
    // Handle response
    if ($response->isSuccess()) {
        // Success
    } elseif ($response->isPending()) {
        // Pending
    } else {
        // Error
    }
    
} catch (IRSMarketException $e) {
    // Handle API error
    error_log($e->getMessage());
}
?>
```

## Troubleshooting

### Error 13: Invalid IP Address

**Solusi:** Whitelist IP server Anda di dashboard IRSMarket

### Error 11: Invalid API Key

**Solusi:** 
- Cek kembali API Key di dashboard IRSMarket
- Ensure no extra spaces/characters
- API Key case-sensitive

### Error 12: Invalid API Secret

**Solusi:**
- Cek kembali API Secret di dashboard
- API Secret case-sensitive
- Jangan commit credentials ke repository

### cURL Error

**Solusi:**
- Pastikan cURL extension sudah enabled (`php -m | grep curl`)
- Jika menggunakan SSL, pastikan CA certificates terbaru
- Setup proxy jika berada di jaringan internal

### Memory Limit

Jika mendapat "Allowed memory size" error:

```php
ini_set('memory_limit', '256M');
```

### Timeout Exception

Jika request sering timeout, tingkatkan timeout duration:

```php
$client = new Client('api_key', 'api_secret', timeout: 60);
```

## Docker Setup (Optional)

Jika menggunakan Docker:

```dockerfile
FROM php:8.1-fpm

RUN docker-php-ext-install json curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

RUN composer install

CMD ["php-fpm"]
```

## Next Steps

Setelah berhasil setup:

1. Lihat [README.md](../README.md) untuk API reference
2. Lihat folder `examples/` untuk contoh penggunaan
3. Baca [dokumentasi API lengkap](https://irsmarket.com/integrasi)

## Support

Jika ada masalah:

1. Cek error message dengan detail
2. Lihat troubleshooting section di atas
3. Baca dokumentasi API
4. Hubungi support: api-support@aviana.co.id
