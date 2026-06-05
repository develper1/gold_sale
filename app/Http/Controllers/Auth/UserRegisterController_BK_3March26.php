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

        // Send simple welcome email inline
        try {
            \Mail::send([], [], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Welcome to OasisMint - Your Account is Active!')
                        ->html("
                            <h1>Welcome to OasisMint, {$user->name}!</h1>
                            <p>Your account has been successfully created and is <strong>now active</strong>.</p>
                            <p>You can now:</p>
                            <ul>
                                <li>Browse our products</li>
                                <li>Add items to your cart</li>
                                <li>Complete purchases</li>
                                <li>Track your orders</li>
                            </ul>
                            <p><a href='" . route('cart.view') . "' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>Start Shopping</a></p>
                            <p>If you have any questions, contact us at " . env('MAIL_ADMIN_EMAIL', 'info@oasismint.com') . "</p>
                            <p>Thanks,<br>OasisMint Team</p>
                        ");
            });
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->intended(route('cart.view'));
    }
}
