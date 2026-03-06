<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedNotification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'status' => 'required|in:pending,paid,shipped,refunded,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->status === 'shipped') {
            Mail::to($order->billing_email)->send(new OrderShippedNotification($order));
        }

        return back()->with('success', 'Order status updated to ' . $order->status . '.');
    }

    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $order = Order::findOrFail($id);
        $order->admin_notes = $request->input('admin_notes');
        $order->save();

        return back()->with('success', 'Admin notes saved.');
    }
} 