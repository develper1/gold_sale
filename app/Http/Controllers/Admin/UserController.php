<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', 0)->withCount('orders')->get();

        return view('admin.users.index')->with('users', $users);
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
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(8)];
            $rules['password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        $user->name                    = $validated['name'];
        $user->email                   = $validated['email'];
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
