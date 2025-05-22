<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\SubscriberDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for subscribing!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.'
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
} 