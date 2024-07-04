<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminHomeController extends Controller
{
    public function index(){
        return view('admin.home');
    }

    public function users(){

        $users = User::where('is_admin', 0)->get();

        return view('admin.users')->with('users', $users);
    }
}
