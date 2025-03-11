<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        $this->tbcEndpoint = config('services.tbc.endpoint'); // TBC API endpoint
        $this->bogEndpoint = config('services.bog.endpoint'); // BOG API endpoint (adjust later)
        $this->tbcApiKey   = config('services.tbc.api_key');
        $this->tbcAppId    = config('services.tbc.app_id'); // Chardin Gallery App ID
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
        $payload = [
            'order' => [
                'orderId'     => $paymentData['order_id'],
                'amount'      => $paymentData['amount'] * 100, // Amount in smallest currency unit (e.g., GEL * 100)
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
        ];

        try {
            // Guzzle HTTP request to TBC API
            $response = $this->client->post($this->tbcEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tbcApiKey,
                    'X-App-Id'      => $this->tbcAppId,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Force TLS 1.2 or TLS 1.3 for secure communication
                    CURLOPT_SSL_VERIFYHOST => 2, // Ensure SSL verification
                    CURLOPT_SSL_VERIFYPEER => true, // Enable SSL peer verification
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['paymentLink']) && $result['paymentLink']) {
                return $result;
            } else {
                throw new Exception('TBC Payment failed: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $message = $response ? $response->getBody()->getContents() : $e->getMessage();
            throw new Exception('TBC Payment Exception: ' . $message);
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
        ];

        try {
            // Guzzle HTTP request to BOG API
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
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $message = $response ? $response->getBody()->getContents() : $e->getMessage();
            throw new Exception('BOG Payment Exception: ' . $message);
        } catch (Exception $e) {
            throw new Exception('BOG Payment Exception: ' . $e->getMessage());
        }
    }
}
