<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', 0)->withCount('orders');

        // Search by name or email
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'email', 'created_at', 'id'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('name', 'asc');
        }

        $users = $query->get();

        return view('admin.users.index', compact('users', 'sortBy', 'sortDir'));
    }

    public function show($id)
    {
        $user = User::where('is_admin', 0)->with(['orders.items'])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::where('is_admin', 0)->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('is_admin', 0)->findOrFail($id);

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'allow_different_shipping' => 'nullable|boolean',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_address_1' => 'nullable|string|max:255',
            'shipping_address_2' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:50',
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(8)];
            $rules['password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        $user->name                    = $validated['name'];
        $user->email                   = $validated['email'];
        $user->shipping_country        = $validated['shipping_country'] ?? null;
        $user->shipping_address_1      = $validated['shipping_address_1'] ?? null;
        $user->shipping_address_2      = $validated['shipping_address_2'] ?? null;
        $user->shipping_city           = $validated['shipping_city'] ?? null;
        $user->shipping_state          = $validated['shipping_state'] ?? null;
        $user->shipping_postcode       = $validated['shipping_postcode'] ?? null;
        // Checkbox: present = 1, absent = 0
        $user->allow_different_shipping = $request->boolean('allow_different_shipping');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::where('is_admin', 0)->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // Legacy route kept for backward compatibility
    public function users()
    {
        return $this->index();
    }
}
