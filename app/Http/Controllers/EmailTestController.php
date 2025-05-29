<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function showTestForm()
    {
        return view('email-test');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email from your application. If you received this, your email configuration is working correctly!', function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Email');
            });

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
} 