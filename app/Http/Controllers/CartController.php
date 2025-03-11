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

            $newQuantity = $validated['quantity'];
            $oldQuantity = $item->quantity;
            $difference = $newQuantity - $oldQuantity;

            if ($difference > 0) {
                if ($item->artwork->stock < $difference) {
                    throw new \Exception('Not enough stock available');
                }
                $item->artwork->decrement('stock', $difference);
            } elseif ($difference < 0) {
                $item->artwork->increment('stock', abs($difference));
            }

            $item->update(['quantity' => $newQuantity]);

            return redirect()->back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function remove(Request $request, $cartItemId)
    {
        try {
            $this->cartService->removeFromCart($cartItemId);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Item removed successfully',
                    'cart_count' => auth()->user()->cart->items->count()
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
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
