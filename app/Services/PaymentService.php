<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected Client $client;
    protected string $tbcEndpoint;
    protected string $tbcApiKey;
    protected string $tbcAppId;
    protected string $tbcSecret;
    protected bool $sandbox;

    public function __construct()
    {
        $this->tbcEndpoint = config('services.tbc.endpoint');
        $this->tbcApiKey   = config('services.tbc.api_key');
        $this->tbcAppId    = config('services.tbc.app_id');
        $this->tbcSecret   = config('services.tbc.secret');
        $this->sandbox     = config('services.tbc.sandbox', true);

        $this->client = new Client([
            'base_uri' => $this->tbcEndpoint,
            'timeout'  => 30.0,
        ]);
    }

    public function charge($paymentMethod, array $paymentData)
    {
        if ($paymentMethod === 'tbc') {
            return $this->chargeTBC($paymentData);
        }

        throw new \Exception('Unsupported payment method.');
    }

    protected function chargeTBC(array $paymentData)
    {
        $timestamp = time();


        $signatureString = $this->tbcAppId
            . $paymentData['order_id']
            . $paymentData['amount']
            . $paymentData['currency']
            . $timestamp;
        $signature = hash_hmac('sha256', $signatureString, $this->tbcSecret);

        $payload = [
            'order_id'       => $paymentData['order_id'],
            'amount'         => $paymentData['amount'],
            'currency'       => $paymentData['currency'],
            'description'    => $paymentData['description'],
            'language'       => $paymentData['language'] ?? 'en',
            'success_url'    => $paymentData['success_url'],
            'fail_url'       => $paymentData['fail_url'],
            'customer_email' => $paymentData['customer_email'],
            'customer_phone' => $paymentData['customer_phone'],
            'timestamp'      => $timestamp,
            'signature'      => $signature,
        ];

        try {
            $response = $this->client->post('', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tbcApiKey,
                    'X-App-Id'      => $this->tbcAppId,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'User-Agent'    => 'ChardinGalleryApp/1.0',
                ],
                'json'   => $payload,
                // Always enable SSL verification in production environments.
                'verify' => true,
            ]);

            $body = json_decode((string)$response->getBody(), true);
            Log::info('TBC Payment Response:', $body);

            return $body;
        } catch (\Exception $e) {
            Log::error('TBC Payment Error: ' . $e->getMessage());
            throw new \Exception('Payment processing failed: ' . $e->getMessage());
        }
    }
}
