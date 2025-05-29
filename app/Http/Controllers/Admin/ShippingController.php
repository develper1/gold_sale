<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::latest()->get();
        return view('admin.shipping.index', compact('shippings'));
    }

    public function create()
    {
        return view('admin.shipping.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0',
            'shipping_charges' => 'required|numeric|min:0',
        ]);

        Shipping::create($request->all());

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping created successfully.');
    }

    public function edit(Shipping $shipping)
    {
        return view('admin.shipping.edit', compact('shipping'));
    }

    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0',
            'shipping_charges' => 'required|numeric|min:0',
        ]);

        $shipping->update($request->all());

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping updated successfully.');
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping deleted successfully.');
    }
} 