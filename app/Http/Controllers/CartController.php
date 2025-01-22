<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Artwork $artwork)
    {
        try {
            $this->cartService->addToCart($artwork->id, $request->quantity);
            return redirect()->back()->with('success', 'Item added to cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, CartItem $item)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);

            $difference = $validated['quantity'] - $item->quantity;

            if ($item->artwork->stock < $difference) {
                throw new \Exception('Not enough stock available');
            }

            $item->artwork->decrement('stock', $difference);
            $item->update(['quantity' => $validated['quantity']]);

            return redirect()->back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove($cartItemId)
    {
        $this->cartService->removeFromCart($cartItemId);
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
