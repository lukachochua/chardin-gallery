<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.artwork'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.artwork.artist']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,cancelled,completed']);

        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Order status updated');
    }
}
