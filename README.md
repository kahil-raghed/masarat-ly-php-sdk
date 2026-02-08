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
use KahilRaghed\MasaratLy\ApiResponse;

$masarat = new MasaratLy('https://api.masarat.ly');

// Sign in
$response = $masarat->signIn(
    userId: 'your_user_id',
    pin: 'your_pin',
    providerId: 'your_provider_id',
    authUserType: 'merchant'
);

if ($response->success()) {
    // Cache token and expiry
    $tokenValidTo = $response->content['validTo'];
    $token = $response->content['value'];
    $masarat->setToken($token);
} else {
    echo "Sign in failed: " . $response->message;
    return;
}

// Open payment session
$sessionResponse = $masarat->openSession(
    amount: 100.00,
    identityCard: '123456789',
    transactionId: 'ORDER-12345',
    onlineOperation: 1 // 1 = Sell, 2 = Refund
);

if ($sessionResponse->success()) {
    // Session token and expiry
    $sessionTokenValidTo = $sessionResponse->content['validTo'];
    $sessionToken = $sessionResponse->content['value'];
} else {
    echo "Session failed: " . $sessionResponse->message;
    return;
}

// Complete transaction with OTP
$result = $masarat->completeSession(
    sessionToken: $sessionToken,
    otp: '123456'
);

if ($result->success()) {
    echo "Payment successful!";
} else {
    echo "Payment failed: " . $result->message;
}
```

## API Methods

### `signIn($userId, $pin, $providerId, $authUserType): ApiResponse`
Authenticate and obtain a token. Returns an `ApiResponse` object.

### `openSession($amount, $identityCard, $transactionId, $onlineOperation): ApiResponse`
Open a payment session. Returns an `ApiResponse` object with session data including `value` (session token).

**Parameters:**
- `$amount` - Transaction amount
- `$identityCard` - Customer card ID (9-10 digits)
- `$transactionId` - Unique transaction ID
- `$onlineOperation` - 1 for Sell, 2 for Refund

### `completeSession($sessionToken, $otp): ApiResponse`
Complete the transaction with OTP verification. Returns an `ApiResponse` object.

### `getToken()` / `setToken($token)`
Get or set the authentication token.

## License

MIT

## Author

Raghed Kahil - kahilraghed@gmail.com
