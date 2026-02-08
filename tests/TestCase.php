<?php

namespace Tests;

use KahilRaghed\MasaratLy\MasaratLy;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public MasaratLy $masaratLy;
    protected function setUp(): void
    {
        $this->masaratLy = new MasaratLy(
            getenv('BASE_URL'),
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );
        parent::setUp();
    }
}
