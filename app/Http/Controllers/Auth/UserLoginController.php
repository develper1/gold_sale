<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $returnUrl = $request->query('return');
        if ($returnUrl) {
            session()->put('url.intended', $returnUrl);
        } elseif (!session()->has('url.intended')) {
            $previousUrl = url()->previous();
            if ($previousUrl && !str_contains($previousUrl, '/login') && !str_contains($previousUrl, '/register')) {
                session()->put('url.intended', $previousUrl);
            }
        }

        return view('auth.login-page');
    }

    public function login(Request $request)
    {
        $request->validate([

            'email' => 'required|email',
            'password' => 'required'
           ]);

           
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            if (!Auth::guard('web')->user()->is_admin) {
                return redirect()->intended(route('cart.view'));
            } else {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Unauthorized.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        session()->forget('cart');
        Auth::guard('web')->logout();
        return redirect('/home');
    }

    public function showForgetPassForm()
    {
        return view('auth.forget-password');
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'user_login' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            ['email' => $request->user_login]
        );

        return back()->with(
            $status === Password::RESET_LINK_SENT
                ? ['status' => __($status)]
                : ['error' => __($status)]
        );
    }
}
