<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    public function createPaymentIntent($amount)
    {
        try {
            return PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createCharge($paymentMethodId, $amount)
    {
        try {
            return PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd', // or another currency
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('payment.create')
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Log the error message for debugging
            Log::error("Stripe API Error: " . $e->getMessage());
            throw new Exception("Stripe payment failed: " . $e->getMessage());
        }
    }

    public function confirmPaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId)->confirm();
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function cancelPaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId)->cancel();
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
