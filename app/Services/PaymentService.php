<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class PaymentService
{
    protected Client $client;
    protected string $tbcEndpoint;
    protected string $bogEndpoint;
    protected string $tbcApiKey;
    protected string $tbcAppId;
    protected string $bogApiKey;

    public function __construct()
    {
        $this->client = new Client();
        // TBC E-Commerce API endpoint per official documentation.
        $this->tbcEndpoint = config('services.tbc.endpoint'); // e.g. https://checkout.tbcbank.ge/api/checkout/v1/transactions
        $this->bogEndpoint = config('services.bog.endpoint'); // Update this when BOG details are available.
        $this->tbcApiKey   = config('services.tbc.api_key');
        $this->tbcAppId    = config('services.tbc.app_id');  // Chardin Gallery App ID: 6e86edd7-7231-4322-9f08-6486e24674c1
        $this->bogApiKey   = config('services.bog.api_key');
    }

    /**
     * Process a charge with the selected payment method.
     *
     * @param string $paymentMethod Either 'tbc' or 'bog'
     * @param array $paymentData
     * @return array
     * @throws Exception
     */
    public function charge(string $paymentMethod, array $paymentData): array
    {
        switch ($paymentMethod) {
            case 'tbc':
                return $this->chargeTBC($paymentData);
            case 'bog':
                return $this->chargeBOG($paymentData);
            default:
                throw new Exception("Unsupported payment method: " . $paymentMethod);
        }
    }

    /**
     * Process payment via TBC using the E-Commerce API.
     */
    protected function chargeTBC(array $paymentData): array
    {
        // Build the payload following TBC's documentation.
        // Note: Amount must be provided in the smallest currency unit (e.g. GEL amount * 100).
        $payload = [
            'order' => [
                'orderId'     => $paymentData['order_id'],
                'amount'      => $paymentData['amount'] * 100,
                'currency'    => $paymentData['currency'] ?? 'GEL',
                'description' => $paymentData['description'] ?? 'Payment for Order ' . $paymentData['order_id'],
            ],
            'redirect' => [
                'successUrl' => $paymentData['success_url'],
                'failUrl'    => $paymentData['fail_url'],
            ],
            'customer' => [
                'email' => $paymentData['customer_email'] ?? '',
                'phone' => $paymentData['customer_phone'] ?? '',
            ],
            // Additional fields can be added here if required.
        ];

        try {
            $response = $this->client->post($this->tbcEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tbcApiKey,
                    'X-App-Id'      => $this->tbcAppId, // Include the Chardin Gallery App ID
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody(), true);

            // A successful response includes a 'paymentLink' to which the customer should be redirected.
            if (isset($result['paymentLink']) && $result['paymentLink']) {
                return $result;
            } else {
                throw new Exception('TBC Payment failed: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            throw new Exception('TBC Payment Exception: ' . $e->getMessage());
        }
    }

    /**
     * Process payment via BOG.
     * (This is a placeholder – adjust it using BOG’s official documentation.)
     */
    protected function chargeBOG(array $paymentData): array
    {
        $payload = [
            'order' => [
                'orderId'     => $paymentData['order_id'],
                'amount'      => $paymentData['amount'], // Adjust if minor units are required
                'currency'    => $paymentData['currency'] ?? 'GEL',
                'description' => $paymentData['description'] ?? 'Payment for Order ' . $paymentData['order_id'],
            ],
            'redirect' => [
                'successUrl' => $paymentData['success_url'],
                'failUrl'    => $paymentData['fail_url'],
            ],
            // Additional fields for BOG can be added here.
        ];

        try {
            $response = $this->client->post($this->bogEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->bogApiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['paymentLink']) && $result['paymentLink']) {
                return $result;
            } else {
                throw new Exception('BOG Payment failed: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            throw new Exception('BOG Payment Exception: ' . $e->getMessage());
        }
    }
}
