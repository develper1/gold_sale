<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaidController extends Controller
{
    /**
     * Get the base URL for Plaid API based on the environment.
     */
    private function getPlaidBaseUrl(): string
    {
        $env = env('PLAID_ENV', 'sandbox');
        switch ($env) {
            case 'production':
                return 'https://production.plaid.com';
            case 'development':
                return 'https://development.plaid.com';
            case 'sandbox':
            default:
                return 'https://sandbox.plaid.com';
        }
    }

    /**
     * Create a Plaid Link Token.
     */
    public function createLinkToken(Request $request)
    {
        $clientId = env('PLAID_CLIENT_ID');
        $secret = env('PLAID_SECRET');

        if (!$clientId || !$secret) {
            Log::error('Plaid credentials are not configured in .env');
            return response()->json(['error' => 'Plaid is not configured properly on the server.'], 500);
        }

        $userId = auth()->id() ? (string)auth()->id() : 'guest_' . session()->getId();

        $payload = [
            'client_id' => $clientId,
            'secret' => $secret,
            'client_name' => config('app.name', 'Oasismint'),
            'country_codes' => ['US'],
            'language' => 'en',
            'user' => [
                'client_user_id' => $userId,
            ],
            'products' => ['auth', 'identity'],
        ];

        try {
            $response = Http::post($this->getPlaidBaseUrl() . '/link/token/create', $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Plaid Link Token Create Failed: ' . $response->body());
            return response()->json([
                'error' => 'Failed to create Plaid link token.',
                'details' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            Log::error('Plaid Link Token Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while connecting to Plaid.'], 500);
        }
    }
}
