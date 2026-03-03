<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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
        $validated = $request->validate([
            'register_name' => 'required|string|max:255',
            'register_email' => 'required|string|email|max:255|unique:users,email',
            'register_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'g-recaptcha-response' => 'required',
        ], [
            'register_name.required' => 'Please enter your name.',
            'register_email.required' => 'Please enter your email address.',
            'register_email.email' => 'Please enter a valid email address.',
            'register_email.unique' => 'This email is already registered. Please log in or use Forgot Password if you do not remember your password.',
            'register_password.required' => 'Please enter a password.',
            'register_password.confirmed' => 'The password confirmation does not match.',
            'register_password.min' => 'Password must be at least 8 characters.',
            'register_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA verification.',
        ]);

        // Verify reCAPTCHA with Google
        $recaptchaVerification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$recaptchaVerification->json('success')) {
            return back()
                ->withInput($request->except('register_password', 'register_password_confirmation'))
                ->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.']);
        }

        try {
            $user = User::create([
                'name' => $validated['register_name'],
                'email' => $validated['register_email'],
                'password' => Hash::make($validated['register_password']),
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000 && (str_contains($e->getMessage(), 'users_email_unique') || str_contains($e->getMessage(), 'Duplicate entry'))) {
                throw ValidationException::withMessages([
                    'register_email' => ['This email is already registered. Please log in or use Forgot Password if you do not remember your password.'],
                ]);
            }
            throw $e;
        }

        event(new Registered($user));
        Auth::guard('web')->login($user);

        return redirect()->route('verification.notice');
    }
}
