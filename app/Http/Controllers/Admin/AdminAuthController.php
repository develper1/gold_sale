<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function index(){
        return view('admin.auth.login');
    }

    public function login(Request $request){
        
       $request->validate([

        'email' => 'required|email',
        'password' => 'required'
       ]);

       $credentials = $request->only('email', 'password');

       if (Auth::guard('admin')->attempt($credentials)) {
           if (Auth::guard('admin')->user()->is_admin) {
               return redirect()->intended(route('admin.home'));
           } else {
               Auth::guard('admin')->logout();
               return back()->withErrors(['email' => 'Unauthorized.']);
           }
       }

       return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
