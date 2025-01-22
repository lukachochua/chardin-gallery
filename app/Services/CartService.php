<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart()
    {
        if (!Auth::check()) {
            throw new \Illuminate\Auth\AuthenticationException();
        }

        return Auth::user()->cart()->firstOrCreate();
    }

    public function addToCart($artworkId, $quantity = 1)
    {
        $cart = $this->getCart();

        $cartItem = $cart->items()->updateOrCreate(
            ['artwork_id' => $artworkId],
            ['quantity' => DB::raw("quantity + $quantity")]
        );

        return $cart->load('items.artwork');
    }

    public function removeFromCart($artworkId)
    {
        $cart = $this->getCart();
        $cart->items()->where('artwork_id', $artworkId)->delete();

        return $cart->load('items.artwork');
    }

    public function getCartTotal()
    {
        return $this->getCart()->items->sum(function ($item) {
            return $item->quantity * $item->artwork->price;
        });
    }
}
