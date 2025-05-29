<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StateFee;
use Illuminate\Http\Request;

class StateFeeController extends Controller
{
    public function index()
    {
        $stateFees = StateFee::latest()->get();
        return view('admin.statefee.index', compact('stateFees'));
    }

    public function edit(StateFee $stateFee)
    {
        return view('admin.statefee.edit', compact('stateFee'));
    }

    public function update(Request $request, StateFee $stateFee)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0',
        ]);

        $stateFee->update($request->all());

        return redirect()->route('admin.statefee.index')
            ->with('success', 'State fee updated successfully.');
    }
} 