<?php

use KahilRaghed\MasaratLy\MasaratLy;

test('setCert and getCert work with a string path', function () {
    $sdk = new MasaratLy('https://example.com');
    $sdk->setCert('/path/to/client.pem');
    expect($sdk->getCert())->toBe('/path/to/client.pem');
});

test('setCert and getCert work with path and password array', function () {
    $sdk = new MasaratLy('https://example.com');
    $sdk->setCert(['/path/to/client.pem', 'secret']);
    expect($sdk->getCert())->toBe(['/path/to/client.pem', 'secret']);
});

test('setSslKey and getSslKey work with a string path', function () {
    $sdk = new MasaratLy('https://example.com');
    $sdk->setSslKey('/path/to/private.key');
    expect($sdk->getSslKey())->toBe('/path/to/private.key');
});

test('setSslKey and getSslKey work with path and password array', function () {
    $sdk = new MasaratLy('https://example.com');
    $sdk->setSslKey(['/path/to/private.key', 'secret']);
    expect($sdk->getSslKey())->toBe(['/path/to/private.key', 'secret']);
});

test('cert and sslKey are empty by default', function () {
    $sdk = new MasaratLy('https://example.com');
    expect($sdk->getCert())->toBe('');
    expect($sdk->getSslKey())->toBe('');
});
