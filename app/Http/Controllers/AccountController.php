<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class AccountController extends Controller
{
    public function completeProfile()
    {
        $user = Auth::user();
        if ($user->profile_completed_at) {
            return redirect()->route('account')->with('success', 'Your profile is already complete.');
        }
        return view('account.complete-profile', compact('user'));
    }

    public function storeCompleteProfile(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_completed_at) {
            return redirect()->route('account')->with('success', 'Your profile is already complete.');
        }
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:50',
            'shipping_address_1' => 'required|string|max:255',
            'shipping_address_2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_country' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_postcode' => 'required|string|max:50',
            'agree_policies' => 'required|accepted',
        ], [
            'agree_policies.accepted' => 'You must read and agree to the Sales Policy, Returns & Exchanges Policy, Terms of Sale, and Anti Money Laundering Policy.',
        ]);
        $user->name = $request->account_name;
        $user->email = $request->account_email;
        $user->phone = $request->phone;
        $user->shipping_address_1 = $request->shipping_address_1;
        $user->shipping_address_2 = $request->shipping_address_2;
        $user->shipping_city = $request->shipping_city;
        $user->shipping_country = $request->shipping_country;
        $user->shipping_state = $request->shipping_state;
        $user->shipping_postcode = $request->shipping_postcode;
        $user->profile_completed_at = now();
        $user->policies_agreed_at = now();
        $user->save();
        return redirect()->route('account')->with('success', 'Your profile has been completed. You can now place orders.');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = [];
        if ($user) {
            $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        return view('my-account', compact('orders', 'user'));
    }

    public function showOrder($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();
        return view('order-detail', compact('order'));
    }

    public function orderDetailAjax($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['items.product.images'])->where('id', $orderId)->where('user_id', $user->id)->firstOrFail();
        // Prepare order and items data for JSON
        $orderData = [
            'id' => $order->id,
            'status' => $order->status,
            'created_at' => $order->created_at->format('F d, Y'),
            'subtotal' => $order->subtotal,
            'shipping_fee' => $order->shipping_fee,
            'state_fee' => $order->state_fee,
            'service_fee' => $order->service_fee,
            'credit_card_fee' => $order->credit_card_fee,
            'payment_method' => $order->payment_method,
            'total' => $order->total,
            'items' => $order->items->map(function($item) {
                $image = null;
                if ($item->product && $item->product->images && $item->product->images->count() > 0) {
                    $image = $item->product->images->first()->image_path;
                    if ($image && !str_starts_with($image, 'http')) {
                        $image = asset('storage/app/public/' . $image);
                    }
                }
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                    'image' => $image,
                ];
            })->toArray(),
        ];
        return response()->json(['order' => $orderData]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'account_first_name' => 'required|string|max:255',
            'account_email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_address_1' => 'nullable|string|max:255',
            'shipping_address_2' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:50',
            'password_1' => 'nullable|string|min:8|same:password_2',
            'password_2' => 'nullable|string|min:8',
        ];
        $messages = [
            'password_1.same' => 'The new password and confirm new password must match.',
        ];
        // Only require current password if changing password or email
        $changingPassword = $request->filled('password_1');

        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // If changing password or email, verify current password
        if (($changingPassword) && !\Hash::check($request->password_current, $user->password)) {
            $error = ['password_current' => ['The current password is incorrect.']];
            if ($request->ajax()) {
                return response()->json(['errors' => $error], 422);
            }
            return redirect()->back()->withErrors($error)->withInput();
        }
        $user->name = $request->account_first_name;
        $user->email = $request->account_email;
        $user->phone = $request->phone;
        $user->shipping_country = $request->shipping_country;
        $user->shipping_address_1 = $request->shipping_address_1;
        $user->shipping_address_2 = $request->shipping_address_2;
        $user->shipping_city = $request->shipping_city;
        $user->shipping_state = $request->shipping_state;
        $user->shipping_postcode = $request->shipping_postcode;
        if ($changingPassword) {
            $user->password = bcrypt($request->password_1);
        }
        $user->save();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Account details updated successfully.']);
        }
        return redirect()->back()->with('success', 'Account details updated successfully.');
    }
} 