<?php

namespace KahilRaghed\MasaratLy;

use GuzzleHttp\Client as Guzzle;

class MasaratLy
{
    protected string $baseUrl;
    protected string $token = '';

    public function __construct(
        string $baseUrl
    ) {
        $this->baseUrl = $baseUrl;
    }


    public const SIGN_IN_PATH = '/api/OnlinePaymentServices/Signin';
    public const OPEN_SESSION_PATH = '/api/OnlinePaymentServices/OpenSession';
    public const COMPLETE_SESSION_PATH = '/api/OnlinePaymentServices/CompleteSession';
    public const BALANCE_PATH = '/api/OnlinePaymentServices/Balance';

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getHttpClient(): Guzzle
    {
        return new Guzzle([
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function signIn(
        string $userId,
        string $pin,
        string $providerId,
        string $authUserType
    ) {
        $payload = [
            'userId' => $userId,
            'pin' => $pin,
            'providerId' => $providerId,
            'authUserType' => $authUserType,
        ];

        $response = $this->getHttpClient()->post(
            $this->baseUrl . self::SIGN_IN_PATH,
            [
                'json' => $payload,
            ]
        );

        $content = $response->getBody()->getContents();

        $data = json_decode($content, true);

        $this->setToken($data['content']['value']);

        return $data;
    }

    /**
     * 
     * @param float $amount
     * @param string $identityCard Customer Card ID: All banks must enter 9 digits (card number + prefix), In the case of the Trade and Development Bank, 10 digits (card number only) must be entered
     * @param string $transactionId
     * @param int $onlineOperation // 1 = Sell/2 = Recover
     */
    public function openSession(
        float $amount,
        string $identityCard,
        string $transactionId,
        int $onlineOperation
    ) {
        $payload = [
            'amount' => $amount,
            'identityCard' => $identityCard,
            'transactionId' => $transactionId,
            'onlineOperation' => $onlineOperation,
        ];

        $response = $this->getHttpClient()->post(
            $this->baseUrl . self::OPEN_SESSION_PATH,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                ],
                'json' => $payload,
            ]
        );

        $content = $response->getBody()->getContents();

        $data = json_decode($content, true);

        return $data;
    }

    /**
     * Confirmation of the possibility of making the financial transaction (sale/recovery)
     * @param string $sessionToken
     * @param string $otp
     */
    public function completeSession(
        string $sessionToken,
        string $otp
    ) {
        $payload = [
            'otp' => $otp,
        ];

        $response = $this->getHttpClient()->post(
            $this->baseUrl . self::COMPLETE_SESSION_PATH . '/' . $sessionToken,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $sessionToken,
                ],
                'json' => $payload,
            ]
        );

        $content = $response->getBody()->getContents();

        $data = json_decode($content, true);

        return $data;
    }
}