# Project Structure

Berikut adalah struktur lengkap library IRSMarket API yang telah dibuat:

```
IRSMARKET_API/PHP/
│
├── composer.json                          # Package configuration
├── README.md                              # Documentation lengkap
├── INSTALLATION.md                        # Setup guide
├── CONTRIBUTING.md                        # Contribution guidelines
├── phpunit.xml                            # PHPUnit configuration
├── .gitignore                             # Git ignore patterns
├── .env.example                           # Environment template
│
├── src/                                   # Main source code
│   ├── Client.php                         # Main API client
│   ├── Config.php                         # Configuration management
│   │
│   ├── Http/
│   │   └── HttpClient.php                # HTTP request handler (Guzzle wrapper)
│   │
│   ├── Request/
│   │   ├── TransactionRequest.php        # Transaction request builder
│   │   └── BalanceRequest.php            # Balance request builder
│   │
│   ├── Response/
│   │   └── Response.php                  # API response handler
│   │
│   └── Exception/
│       └── IRSMarketException.php        # Custom exception class
│
├── config/
│   └── IRSMarketConfig.php               # Configuration helper
│
├── examples/                              # Usage examples
│   ├── 01-basic-transaction.php          # Basic transaction example
│   ├── 02-check-balance.php              # Balance checking example
│   ├── 03-get-method-with-signature.php  # GET method + signature example
│   ├── 04-advanced-error-handling.php    # Error handling example
│   └── 05-open-denomination.php          # Open denomination example
│
└── tests/                                 # Unit tests
    ├── RequestTest.php                   # Request building tests
    └── ResponseTest.php                  # Response handling tests
```

## File Details

### Root Files
- **composer.json**: Package configuration, dependencies (guzzlehttp/guzzle), autoloading rules
- **README.md**: Complete documentation in Indonesian with API reference, examples, response codes
- **INSTALLATION.md**: Step-by-step setup guide with troubleshooting
- **CONTRIBUTING.md**: Guidelines for contributing to the project
- **phpunit.xml**: PHPUnit test configuration
- **.gitignore**: Standard ignore patterns (vendor, .env, etc.)
- **.env.example**: Template for environment variables

### Source Code (src/)

#### Client.php (Main)
- `Client` class - Main entry point
- Methods: `transaction()`, `transactionGet()`, `balance()`
- Handles request/response orchestration

#### Config.php
- `Config` class - Configuration holder
- API credentials management
- Response code mappings (all 19 codes)
- Base URL and timeout settings

#### Http/HttpClient.php
- `HttpClient` class - HTTP requests wrapper
- Uses Guzzle HTTP client
- Methods: `post()`, `get()`
- JSON decode and error handling

#### Request/ Directory
- **TransactionRequest.php**
  - Builder for transaction requests
  - Support for both POST and GET methods
  - MD5 signature generation
  - Query and data building
  
- **BalanceRequest.php**
  - Simple balance request builder
  - Requires only API key and secret

#### Response/Response.php
- `Response` class - Response handler
- Status checking: `isSuccess()`, `isPending()`, `isFailed()`
- Data extraction methods
- Code interpretation
- Magic getter for raw data access

#### Exception/IRSMarketException.php
- Custom exception class
- Captures error code and response data
- Extends standard PHP Exception

### Configuration (config/)
- **IRSMarketConfig.php**
  - Helper class for config management
  - Load from environment variables
  - Load from array/config file
  - Load from .env file

### Examples (examples/)
1. **01-basic-transaction.php** - Simple POST transaction
2. **02-check-balance.php** - Balance checking
3. **03-get-method-with-signature.php** - GET method with MD5 signature
4. **04-advanced-error-handling.php** - Comprehensive error handling with switch cases
5. **05-open-denomination.php** - Custom amount transactions (10k-500k)

### Tests (tests/)
- **RequestTest.php** - 7 test cases for request building
- **ResponseTest.php** - 7 test cases for response handling

## Dependencies

**Required:**
- PHP 7.4+
- guzzlehttp/guzzle ^7.0
- ext-json

**Development:**
- phpunit/phpunit ^9.0|^10.0
- squizlabs/php_codesniffer ^3.7

## API Endpoints Supported

1. **POST /transaction** - Send transaction (POST method)
2. **GET /transaction** - Send transaction (GET method)
3. **POST /balance** - Check account balance

## Features Implemented

✅ Authentication (API Key + Secret or MD5 Signature)
✅ Transaction processing (POST and GET)
✅ Balance checking
✅ Comprehensive error handling
✅ Response status checking
✅ All API response codes mapped
✅ Open denomination support
✅ Configuration management
✅ Complete documentation
✅ Usage examples
✅ Unit tests

## Features NOT Implemented (as requested)

❌ Webhook handling - Explicitly excluded per user request
❌ Seller integration - Only buyer/client implementation
❌ Callback handlers - No webhook callbacks

## Installation & Usage Quick Start

```bash
# Install
composer require irsmarket/api-client

# Use
<?php
use IRSMarket\API\Client;

$client = new Client('api_key', 'api_secret');
$response = $client->transaction('TSEL_5000', 'TRX_001', '081234567890');

if ($response->isSuccess()) {
    echo "Success: " . $response->getReff();
}
?>
```

## Next Steps

1. **Install dependencies**: `composer install`
2. **Setup credentials**: Copy `.env.example` to `.env` and add API key/secret
3. **Whitelist IP**: Add server IP to IRSMarket dashboard
4. **Run tests**: `composer test`
5. **Run examples**: `php examples/01-basic-transaction.php`
6. **Integrate**: Use in your application

---

Created: March 2026
Library Version: 1.0.0
IRSMarket API Version: v1
