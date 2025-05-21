<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::where('is_admin', 0)->get();

        return view('admin.users.index')->with('users', $users);
    }

}
