<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpotTierPrice;
use App\Models\Product;

class SpotTierPriceController extends Controller
{
    public function index()
    {
        $spotTierPrices = SpotTierPrice::paginate(20);
        return view('admin.spot-tier-prices.index', compact('spotTierPrices'));
    }

    public function create()
    {
        return view('admin.spot-tier-prices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tier_start' => 'required|integer',
            'tier_end' => 'required|integer',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
        ]);
        SpotTierPrice::create($data);
        return redirect()->route('admin.spot-tier-prices.index')->with('success', 'Spot Tier Price created successfully.');
    }

    public function edit($id)
    {
        $spotTierPrice = SpotTierPrice::findOrFail($id);
        return view('admin.spot-tier-prices.edit', compact('spotTierPrice'));
    }

    public function update(Request $request, $id)
    {
        $spotTierPrice = SpotTierPrice::findOrFail($id);
        $data = $request->validate([
            'tier_start' => 'required|integer',
            'tier_end' => 'required|integer',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
        ]);
        $spotTierPrice->update($data);
        return redirect()->route('admin.spot-tier-prices.index')->with('success', 'Spot Tier Price updated successfully.');
    }

    public function destroy($id)
    {
        $spotTierPrice = SpotTierPrice::findOrFail($id);
        $spotTierPrice->delete();
        return redirect()->route('admin.spot-tier-prices.index')->with('success', 'Spot Tier Price deleted successfully.');
    }
} 