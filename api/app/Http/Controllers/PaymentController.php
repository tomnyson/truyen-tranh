<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
use App\Models\Setting;

class PaymentController extends Controller
{
    private function initializeStripe()
    {
        $apiVersion = env('STRIPE_API_VERSION');
        if (!$apiVersion) {
            throw new \Exception('Stripe API version is not set in .env');
        }

        $mode = Setting::where('key', 'MODE')->firstOrFail()->value ?? 'dev';
        $stripeSecret = $mode === 'dev' || $mode === 'development'
            ? env('STRIPE_SECRET_DEV')
            : env('STRIPE_SECRET_PROD');

        Stripe::setApiKey($stripeSecret);
        Stripe::setApiVersion($apiVersion);
    }

    private function createStripeCustomer()
    {
        return Customer::create([
            'description' => 'Customer for payment sheet - ' . Auth::user()->name,
            'email' => Auth::user()->email,
        ]);
    }

    private function createEphemeralKey($customerId, $apiVersion)
    {
        return EphemeralKey::create(
            ['customer' => $customerId],
            ['stripe_version' => $apiVersion]
        );
    }

    private function createPaymentIntent(array $data)
    {
        return PaymentIntent::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'customer' => $data['customerId'] ?? null,
            'automatic_payment_methods' => ['enabled' => true],
        ]);
    }

    public function createPaymentSheet(Request $request)
    {
        try {
            $this->initializeStripe();

            $validated = $request->validate([
                'currency' => 'required|string',
                'price' => 'nullable|integer|min:10',
            ]);

            $customer = $this->createStripeCustomer();
            $apiVersion = env('STRIPE_API_VERSION');
            $ephemeralKey = $this->createEphemeralKey($customer->id, $apiVersion);

            $paymentIntent = $this->createPaymentIntent([
                'amount' => ($validated['price'] ?? 1000) * 100, // Default to $10.00
                'currency' => $validated['currency'],
                'customerId' => $customer->id,
            ]);

            return response()->json([
                'paymentIntent' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customer->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPaymentSheetGoogle(Request $request)
    {
        try {
            $this->initializeStripe();

            $validated = $request->validate([
                'currency' => 'required|string',
                'amount' => 'nullable|integer|min:10', // Minimum 50 cents
            ]);

            $paymentIntent = $this->createPaymentIntent([
                'amount' => ($validated['amount'] ?? 1000) * 100, // Default to $10.00
                'currency' => $validated['currency'],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
