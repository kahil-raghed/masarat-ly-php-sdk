<?php

namespace KahilRaghed\MasaratLy;

use GuzzleHttp\Client as Guzzle;

class MasaratLy
{
    protected string $baseUrl;
    protected string $token = '';
    private Guzzle $client;
    
    public function __construct(
        string $baseUrl,
        array $clientConfig = []
    ) {
        $this->baseUrl = $baseUrl;
        $this->client = new Guzzle($clientConfig);
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
        return $this->client;
    }

    public function callApi($path, $data, $headers = [])
    {
        $client = $this->getHttpClient();


        $response = $client->post($this->baseUrl . $path, [
            'json' => $data,
            'headers' => $headers,
        ]);

        $rawData = $response->getBody()->getContents();

        $data = json_decode($rawData, true);

        return ApiResponse::fromBody($data);
    }

    /**
     * Example content:  
     * {
     * "validTo": "2026-03-21T19:48:15Z",
     * "refreshToken": null,
     * "systemIdentity": "123456",
     * "creds": 2,
     * "tag": -1,
     * "value": "ey*****************************************************"
     * }
     * @param string $userId
     * @param string $pin
     * @param string $providerId
     * @param string $authUserType
     * @return ApiResponse
     */
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

        return $this->callApi(self::SIGN_IN_PATH, $payload);
    }

    /**
     * Example content:  
     * {
     * "validTo": "2026-03-21T19:48:15Z",
     * "refreshToken": null,
     * "systemIdentity": "123456",
     * "creds": 2,
     * "tag": -1,
     * "value": "ey*****************************************************"
     * }
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

        return $this->callApi(
            self::OPEN_SESSION_PATH,
            $payload,
            [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ]
        );
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

        return $this->callApi(
            self::COMPLETE_SESSION_PATH,
            $payload,
            [
                'Authorization' => 'Bearer ' . $sessionToken,
            ]
        );
    }
}