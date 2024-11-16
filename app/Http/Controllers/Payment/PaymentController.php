<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPaymentIntent(Request $request)
    {
        $amount = $request->amount;

        $paymentIntent = $this->stripeService->createPaymentIntent($amount);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function createPayment()
    {
        return view('payment');
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric|min:1', // Amount in cents
        ]);

        $paymentMethodId = $request->payment_method_id;
        $amount = $request->amount;

        try {
            // Create and confirm the payment
            $paymentIntent = $this->stripeService->createCharge($paymentMethodId, $amount);

            // Check the status of the PaymentIntent
            if ($paymentIntent->status === 'requires_action') {
                return response()->json([
                    'success' => false,
                    'requires_action' => true,
                    'payment_intent_id' => $paymentIntent->id,
                    'message' => 'Additional action is required to complete the payment.',
                ], 200);
            } elseif ($paymentIntent->status === 'succeeded') {
                // Save successful payment details in the database
                $payment = new Payment();
                $payment->user_id = auth()->id(); // Assuming authentication
                $payment->amount = $amount;
                $payment->payment_method = 'stripe';
                $payment->payment_status = 'succeeded';
                $payment->payment_intent_id = $paymentIntent->id;
                $payment->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successfully processed.',
                    'payment_intent_id' => $paymentIntent->id,
                ], 200);
            }

            // Handle unexpected statuses
            return response()->json([
                'success' => false,
                'message' => 'Unexpected payment status: ' . $paymentIntent->status,
            ], 400);

        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            // Retrieve and confirm the payment intent
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            $paymentIntent->confirm();

            return response()->json([
                'success' => true,
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
            ]);
        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
