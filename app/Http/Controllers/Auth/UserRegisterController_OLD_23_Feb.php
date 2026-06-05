<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
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

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            // 'g-recaptcha-response' => 'required|captcha'
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            // 'g-recaptcha-response.required' => 'Please complete the CAPTCHA verification.',
            // 'g-recaptcha-response.captcha' => 'CAPTCHA verification failed. Please try again.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);

        return redirect()->intended(route('cart.view'));
    }
}
