<?php

namespace Tests\Unit;

use App\Services\PaymentService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Exception;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set the necessary config values for testing.
        config([
            'services.tbc.endpoint' => 'https://checkout.tbcbank.ge/api/checkout/v1/transactions',
            'services.tbc.api_key'  => '52cN0JRKWtG4hjYMFWd2Ahsv7bOXGzOE',
            'services.tbc.app_id'   => '6e86edd7-7231-4322-9f08-6486e24674c1',
            'services.bog.endpoint' => 'https://api.bog.com/payment',
            'services.bog.api_key'  => 'dummy_bog_api_key',
        ]);
    }

    /**
     * Helper method to instantiate PaymentService with a mock Guzzle client.
     */
    protected function getPaymentServiceWithMockResponse(Response $response): PaymentService
    {
        // Create a MockHandler with the provided response.
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // Create an instance of PaymentService.
        $paymentService = new PaymentService();

        // Use reflection to override the internal Guzzle client with our mock client.
        $reflection = new \ReflectionClass($paymentService);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($paymentService, $client);

        return $paymentService;
    }

    /**
     * Test a successful TBC charge response.
     */
    public function testChargeTBCSuccess()
    {
        // Simulate a successful TBC response with a paymentLink.
        $fakeResponseData = [
            'paymentLink' => 'https://checkout.tbcbank.ge/payment-session/abc123'
        ];
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode($fakeResponseData));

        $paymentService = $this->getPaymentServiceWithMockResponse($response);

        $paymentData = [
            'order_id'       => 'order_123',
            'amount'         => 100.00, 
            'currency'       => 'GEL',
            'description'    => 'Test order',
            'success_url'    => 'https://example.com/success',
            'fail_url'       => 'https://example.com/fail',
            'customer_email' => 'test@example.com',
            'customer_phone' => '599123456'
        ];

        $result = $paymentService->charge('tbc', $paymentData);

        $this->assertArrayHasKey('paymentLink', $result);
        $this->assertEquals('https://checkout.tbcbank.ge/payment-session/abc123', $result['paymentLink']);
    }

    /**
     * Test a failure response from TBC (e.g. invalid amount).
     */
    public function testChargeTBCFailure()
    {
        // Simulate a failure response from TBC: no paymentLink and an error message.
        $fakeResponseData = [
            'message' => 'Invalid amount'
        ];
        $response = new Response(400, ['Content-Type' => 'application/json'], json_encode($fakeResponseData));

        $paymentService = $this->getPaymentServiceWithMockResponse($response);

        $paymentData = [
            'order_id'       => 'order_456',
            'amount'         => 0, // Invalid amount to trigger failure.
            'currency'       => 'GEL',
            'description'    => 'Test order',
            'success_url'    => 'https://example.com/success',
            'fail_url'       => 'https://example.com/fail',
            'customer_email' => 'test@example.com',
            'customer_phone' => '599123456'
        ];

        try {
            $paymentService->charge('tbc', $paymentData);
            $this->fail('Expected Exception was not thrown.');
        } catch (Exception $e) {
            // Check that the message contains the expected text, rather than matching the entire structure.
            $this->assertStringContainsString('Invalid amount', $e->getMessage());
        }
    }
}
