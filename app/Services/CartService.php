<?php

namespace App\Services;

use App\Models\Artwork;
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
        DB::beginTransaction();
        try {
            $artwork = Artwork::findOrFail($artworkId);
            $cart = $this->getCart();

            if ($artwork->stock < $quantity) {
                throw new \Exception('Not enough stock available');
            }

            $existingItem = $cart->items()->where('artwork_id', $artworkId)->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $quantity;
                if ($artwork->stock < $newQuantity) {
                    throw new \Exception('Exceeds available stock');
                }

                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'artwork_id' => $artworkId,
                    'quantity' => $quantity
                ]);
            }

            $artwork->decrement('stock', $quantity);

            DB::commit();
            return $cart->load('items.artwork');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $artwork = $cartItem->artwork;

        $artwork->increment('stock', $cartItem->quantity);

        $cartItem->delete();

        return $this->getCart()->load('items.artwork');
    }

    public function getCartTotal()
    {
        return $this->getCart()->items->sum(function ($item) {
            return $item->quantity * $item->artwork->price;
        });
    }
}
