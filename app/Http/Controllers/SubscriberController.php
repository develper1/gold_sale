<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\SubscriberDetail;
use App\Mail\SubscriberWelcome;
use App\Mail\AdminSubscriberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA verification.',
        ]);

        $isAjax = $request->ajax() || $request->wantsJson();

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $recaptchaVerification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$recaptchaVerification->json('success')) {
            $captchaError = 'CAPTCHA verification failed. Please try again.';

            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => $captchaError], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['g-recaptcha-response' => $captchaError])
                ->with('error', $captchaError);
        }

        // Check for duplicate email (case-insensitive) — do not allow duplicates
        $existing = Subscriber::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->first();
        if ($existing) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'This email is already subscribed. Please use a different email address.'], 422);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'This email is already subscribed. Please use a different email address.'])
                ->with('error', 'This email is already subscribed. Please use a different email address.');
        }

        try {
            $subscriber = Subscriber::create([
                'email' => $request->email,
                'source' => $request->input('source', 'landing'),
            ]);

            // Send welcome email to subscriber
            try {
                Mail::to($request->email)->send(new SubscriberWelcome($request->email));
            } catch (Exception $mailException) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => 'Email could not be sent. Please try again.'], 500);
                }
                return redirect()->back()
                    ->with('error', 'Email could not be sent. Please try again.');
            }

            // Send notification to admin (ignore errors)
            try {
                Mail::to(env('MAIL_ADMIN_EMAIL'))->send(new AdminSubscriberNotification($request->email));
            } catch (Exception $adminMailException) {
                Log::error('Admin Notification Mail Error: ' . $adminMailException->getMessage());
            }

            if ($isAjax) {
                return response()->json(['status' => 'success', 'message' => 'Thank you for subscribing!']);
            }
            return redirect()->back()
                ->with('success', 'Thank you for subscribing!');

        } catch (Exception $e) {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong. Please try again.'], 500);
            }
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.');
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

    public function destroyBulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:subscribers,id',
        ]);

        try {
            $count = 0;
            foreach ($request->ids as $id) {
                $subscriber = Subscriber::find($id);
                if ($subscriber) {
                    if ($subscriber->details) {
                        $subscriber->details->delete();
                    }
                    $subscriber->delete();
                    $count++;
                }
            }

            $message = $count === 1
                ? '1 subscriber deleted successfully.'
                : $count . ' subscribers deleted successfully.';

            return redirect()->route('admin.subscribers.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.subscribers.index')
                ->with('error', 'Failed to delete subscribers. Please try again.');
        }
    }

    public function export()
    {
        $subscribers = Subscriber::with('details')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Email',
                'Source',
                'First Name',
                'Last Name',
                'City',
                'Investment Type',
                'Investment Criteria',
                'Contact Preference',
                'Mobile Number',
                'Created At'
            ]);

            // Add data
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->id,
                    $subscriber->email,
                    $subscriber->source ?? 'landing',
                    $subscriber->details ? $subscriber->details->first_name : '-',
                    $subscriber->details ? $subscriber->details->last_name : '-',
                    $subscriber->details ? $subscriber->details->city : '-',
                    $subscriber->details ? implode(', ', array_filter($subscriber->details->investment_type, function($type) { return strtolower($type) !== 'all'; })) : '-',
                    $subscriber->details ? '$' . $subscriber->details->investment_criteria : '-',
                    $subscriber->details ? ucfirst($subscriber->details->contact_preference) : '-',
                    $subscriber->details ? ($subscriber->details->mobile_number ?? '-') : '-',
                    $subscriber->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 