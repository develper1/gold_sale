<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $credit_card_percentage = $setting ? $setting->credit_card_percentage : null;
        return view('admin.settings.index', compact('credit_card_percentage'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'credit_card_percentage' => 'required|numeric|min:0|max:100',
        ]);
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }
        $setting->credit_card_percentage = $request->credit_card_percentage;
        $setting->save();
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
} 