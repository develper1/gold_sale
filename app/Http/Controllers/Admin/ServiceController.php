<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0',
            'services_fee' => 'required|numeric|min:0',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')
            ->with('success', 'Service fee created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0',
            'services_fee' => 'required|numeric|min:0',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')
            ->with('success', 'Service fee updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service fee deleted successfully.');
    }
} 