<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['items', 'items.product.images'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,canceled',
        ]);

        $order = Order::findOrFail($id);
        if ($order->status === 'paid') {
            return back()->with('error', 'Paid orders cannot be updated.');
        }
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated to ' . $order->status . '.');
    }
} 