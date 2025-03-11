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

        // SSL config without certificate validation to troubleshoot
        $sslConfig = [
            'verify' => false, // Temporarily disable SSL verification to test connection
            'curl' => [
                CURLOPT_SSLVERSION => 6, // CURL_SSLVERSION_TLSv1_2 | CURL_SSLVERSION_TLSv1_3
                CURLOPT_SSL_VERIFYHOST => 0, // Temporarily disable for testing
                CURLOPT_SSL_VERIFYPEER => false, // Temporarily disable for testing
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
            ],
        ];

        try {
            $client = new Client($sslConfig);

            // Add more debug info
            $debugRequest = [
                'headers' => [
                    'Authorization' => 'Bearer XXXXX', // masked for security
                    'X-App-Id'      => $this->tbcAppId,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'User-Agent'    => 'ChardinGalleryApp/1.0',
                ],
                'json' => $payload,
                'debug' => true, // Enable debug output
                'connect_timeout' => 10,
                'timeout' => 30,
            ];

            // Use the modified client for this request
            $response = $client->post($this->tbcEndpoint, $debugRequest);

            $result = json_decode($response->getBody(), true);

            if (isset($result['paymentLink']) && $result['paymentLink']) {
                return $result;
            } else {
                throw new Exception('TBC Payment failed: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $message = $response ? $response->getBody()->getContents() : $e->getMessage();

            // Detailed logging
            $context = [
                'error_message' => $e->getMessage(),
                'request_url' => $this->tbcEndpoint,
                'curl_info' => $e->getHandlerContext(),
            ];

            \Illuminate\Support\Facades\Log::error('TBC Connection Failed', $context);

            throw new Exception('TBC Payment Exception: ' . $message);
        } catch (Exception $e) {
            throw new Exception('TBC Payment Exception: ' . $e->getMessage());
        }
    }

    /**
     * Process payment via BOG.
     * (This is a placeholder – adjust it using BOG's official documentation.)
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
