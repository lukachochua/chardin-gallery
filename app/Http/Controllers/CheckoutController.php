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
        // Process payment and create order
        $order = $this->orderService->createOrderFromCart($request->all());

        return redirect()->route('checkout.success', $order);
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
