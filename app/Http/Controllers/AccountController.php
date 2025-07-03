<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = [];
        if ($user) {
            $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        return view('my-account', compact('orders'));
    }
} 