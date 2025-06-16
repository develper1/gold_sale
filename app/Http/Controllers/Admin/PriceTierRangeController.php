<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceTierRange;
use Illuminate\Http\Request;

class PriceTierRangeController extends Controller
{
    public function index()
    {
        $priceTierRanges = PriceTierRange::latest()->get();
        return view('admin.price-tier-ranges.index', compact('priceTierRanges'));
    }

    public function create()
    {
        return view('admin.price-tier-ranges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tier_start' => 'required|integer|min:0',
            'tier_end' => 'nullable|integer|min:0|gt:tier_start',
            'tier_price' => 'required|numeric|min:0',
        ]);

        PriceTierRange::create($request->all());

        return redirect()->route('admin.price-tier-ranges.index')
            ->with('success', 'Price tier range created successfully.');
    }

    public function edit(PriceTierRange $priceTierRange)
    {
        return view('admin.price-tier-ranges.edit', compact('priceTierRange'));
    }

    public function update(Request $request, PriceTierRange $priceTierRange)
    {
        $request->validate([
            'tier_start' => 'required|integer|min:0',
            'tier_end' => 'nullable|integer|min:0|gt:tier_start',
            'tier_price' => 'required|numeric|min:0',
        ]);

        $priceTierRange->update($request->all());

        return redirect()->route('admin.price-tier-ranges.index')
            ->with('success', 'Price tier range updated successfully.');
    }

    public function destroy(PriceTierRange $priceTierRange)
    {
        $priceTierRange->delete();

        return redirect()->route('admin.price-tier-ranges.index')
            ->with('success', 'Price tier range deleted successfully.');
    }
} 