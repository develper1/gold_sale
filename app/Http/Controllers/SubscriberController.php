<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\SubscriberDetail;
use App\Mail\SubscriberWelcome;
use App\Mail\AdminSubscriberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $subscriber = Subscriber::create([
                'email' => $request->email
            ]);

            // Send welcome email to subscriber
            try {
                Mail::to($request->email)->send(new SubscriberWelcome($request->email));
            } catch (Exception $mailException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email could not be sent. Please try again.',
                    'error' => $mailException->getMessage(),
                    'trace' => $mailException->getTraceAsString()
                ], 500);
            }

            // Send notification to admin (ignore errors)
            try {
                Mail::to(env('MAIL_ADMIN'))->send(new AdminSubscriberNotification($request->email));
            } catch (Exception $adminMailException) {
                Log::error('Admin Notification Mail Error: ' . $adminMailException->getMessage());
                // Don't return error response, just log it
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for subscribing!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function storeDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'investment_type' => 'required|array',
            'investment_criteria' => 'required|string',
            'contact_preference' => 'required|in:yes,no',
            'mobile_number' => 'required_if:contact_preference,yes|nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            SubscriberDetail::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for completing your profile! We will contact you soon.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function index()
    {
        $subscribers = Subscriber::with('details')->get();
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy(Subscriber $subscriber)
    {
        try {
            // Delete associated details first
            if ($subscriber->details) {
                $subscriber->details->delete();
            }
            
            // Delete the subscriber
            $subscriber->delete();
            
            return redirect()->route('admin.subscribers.index')
                ->with('success', 'Subscriber deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.subscribers.index')
                ->with('error', 'Failed to delete subscriber. Please try again.');
        }
    }
} 