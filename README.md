# Masarat.ly PHP SDK

A simple PHP SDK for integrating with the Masarat.ly payment gateway.

## Installation

```bash
composer require kahil-raghed/masarat-ly-php-sdk
```

## Requirements

- PHP >= 7.4
- GuzzleHTTP >= 6.5

## Usage

### Basic Payment

```php
use KahilRaghed\MasaratLy\MasaratLy;

$masarat = new MasaratLy('https://api.masarat.ly');

// Sign in
$result = $masarat->signIn(
    userId: 'your_user_id',
    pin: 'your_pin',
    providerId: 'your_provider_id',
    authUserType: 'merchant'
);


// cache this
$tokenValidTo = $result['content']['validTo'];
$token = $result['content']['value'];



// Open payment session
$session = $masarat->openSession(
    amount: 100.00,
    identityCard: '123456789',
    transactionId: 'ORDER-12345',
    onlineOperation: 1 // 1 = Sell, 2 = Refund
);

// check for expiry, usually you have 3 min window
$sessionTokenValidTo = $session['content']['validTo'];
$sessionToken = $session['content']['value'];

// Complete transaction with OTP
$result = $masarat->completeSession(
    sessionToken: $sessionToken,
    otp: '123456'
);
```

## API Methods

### `signIn($userId, $pin, $providerId, $authUserType)`
Authenticate and obtain a token.

### `openSession($amount, $identityCard, $transactionId, $onlineOperation)`
Open a payment session. Returns session data including `sessionToken`.

**Parameters:**
- `$amount` - Transaction amount
- `$identityCard` - Customer card ID (9-10 digits)
- `$transactionId` - Unique transaction ID
- `$onlineOperation` - 1 for Sell, 2 for Refund

### `completeSession($sessionToken, $otp)`
Complete the transaction with OTP verification.

### `getToken()` / `setToken($token)`
Get or set the authentication token.

## License

MIT

## Author

Raghed Kahil - kahilraghed@gmail.com
