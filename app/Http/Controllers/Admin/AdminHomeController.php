<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminHomeController extends Controller
{
    public function index(){
        $totalUsers = User::where('is_admin', 0)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return view('admin.home', compact('totalUsers', 'totalProducts', 'totalOrders'));
    }

    public function users(){

        $users = User::where('is_admin', 0)->get();

        return view('admin.users')->with('users', $users);
    }
}
