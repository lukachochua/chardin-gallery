<?php

namespace App\Http\Controllers;

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

    public function add(Request $request, $artworkId)
    {
        $this->cartService->addToCart($artworkId, $request->quantity);
        return redirect()->back()->with('success', 'Item added to cart');
    }

    public function update(Request $request, $cartItemId)
    {
        // Implement quantity update logic
    }

    public function remove($cartItemId)
    {
        $this->cartService->removeFromCart($cartItemId);
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
