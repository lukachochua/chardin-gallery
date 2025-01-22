<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrderFromCart($checkoutData)
    {
        return DB::transaction(function () use ($checkoutData) {
            $cartService = app(CartService::class);
            $cart = $cartService->getCart();
            
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $cartService->getCartTotal(),
                'shipping_address' => $checkoutData['shipping_address'],
                'payment_method' => $checkoutData['payment_method'],
                'status' => 'pending'
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $item->artwork_id,
                    'quantity' => $item->quantity,
                    'price' => $item->artwork->price
                ]);
            }

            $cart->items()->delete();
            
            return $order;
        });
    }
}