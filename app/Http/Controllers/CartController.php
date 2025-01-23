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

    public function add(Artwork $artwork, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = auth()->user()->cart()->firstOrCreate();
        $cart->items()->updateOrCreate(
            ['artwork_id' => $artwork->id],
            ['quantity' => $request->quantity]
        );

        return response()->json([
            'message' => 'Artwork added to cart successfully',
            'cart_count' => $cart->items()->count()
        ]);
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

    public function items()
    {
        $cart = auth()->user()->cart()->with(['items.artwork.artist'])->first();

        return response()->json([
            'items' => $cart ? $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'artwork' => [
                        'title' => $item->artwork->title,
                        'price' => $item->artwork->price,
                        'image' => asset('storage/' . $item->artwork->image),
                        'artist' => [
                            'name' => $item->artwork->artist->name
                        ]
                    ]
                ];
            }) : [],
            'totalQuantity' => $cart ? $cart->items->sum('quantity') : 0
        ]);
    }
}
