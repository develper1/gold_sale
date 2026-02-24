<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormNotification;
use App\Mail\ContactConfirmation;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');
        
        $recaptchaVerification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);

        if (!$recaptchaVerification->json('success')) {
            return response()->json([
                'status' => 'error',
                'message' => 'reCAPTCHA verification failed. Please try again.'
            ], 422);
        }

        try {

            // Prepare data
            $name = (string) strip_tags($request->name);
            $email = (string) strip_tags($request->email);
            $message1 = (string) strip_tags($request->message);

            // Send to admin
            \Mail::to(env('MAIL_ADMIN_EMAIL'))->send(new ContactFormNotification($name, $email, $message1));

            // Send confirmation to user
            \Mail::to($email)->send(new ContactConfirmation($name, $email, $message1));

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for your message! We will get back to you within two days.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            \Log::error('Contact form error trace: ' . $e->getTraceAsString());
             
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, there was an error sending your message. Please try again later.'
            ], 500);
        }
    }
}
