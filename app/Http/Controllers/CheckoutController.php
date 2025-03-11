<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = app(CartService::class)->getCart();
        return view('checkout.index', [
            'cart' => $cart,
            'cartTotal' => app(CartService::class)->getCartTotal()
        ]);
    }

    public function process(Request $request)
    {
        // Validate the checkout request.
        $request->validate([
            'payment_method' => 'required|in:tbc,bog',
            'amount'         => 'required|numeric|min:0.01',
            // Add validations for customer details if needed.
        ]);
    
        $paymentMethod = $request->input('payment_method');
    
        // Prepare payment data.
        $paymentData = [
            'order_id'       => uniqid('order_', true),  // Generate a unique order ID.
            'amount'         => $request->input('amount'),
            'currency'       => 'GEL',
            'description'    => 'Payment for Order ' . uniqid(),
            'success_url'    => route('checkout.success'),
            'fail_url'       => route('checkout.cancel'),
            'customer_email' => $request->input('email', ''),
            'customer_phone' => $request->input('phone', ''),
        ];
    
        $paymentService = new \App\Services\PaymentService();
    
        try {
            // Create a payment session and get the payment link.
            $paymentResponse = $paymentService->charge($paymentMethod, $paymentData);
    
            if (isset($paymentResponse['paymentLink'])) {
                // Redirect the customer to the TBC (or BOG) payment page.
                return redirect()->away($paymentResponse['paymentLink']);
            } else {
                throw new \Exception('No payment link provided.');
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout.cancel')->with('error', $e->getMessage());
        }
    }


    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
