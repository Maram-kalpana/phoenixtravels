<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use DB;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'sometimes|string|max:3',
            'description' => 'sometimes|string|max:255',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => $request->currency ?? 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'description' => $request->description ?? '',
                ],
            ]);

            // Create a pending payment record
            Payment::create([
                'payment_intent_id' => $paymentIntent->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'usd',
                'status' => 'requires_payment_method',
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);
            
        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;
                
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
                
            case 'payment_intent.processing':
            case 'payment_intent.canceled':
            case 'payment_intent.requires_action':
                $paymentIntent = $event->data->object;
                $this->updatePaymentStatus($paymentIntent->id, $event->type);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'succeeded',
                'payment_method_details' => $paymentIntent->payment_method,
                'receipt_url' => $paymentIntent->charges->data[0]->receipt_url,
            ]);
            
            // Here you can trigger other actions like sending emails, 
            // updating user account, etc.
        }
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'payment_method_details' => $paymentIntent->last_payment_error->payment_method ?? null,
            ]);
        }
    }

    protected function updatePaymentStatus($paymentIntentId, $status)
    {
        $statusMap = [
            'payment_intent.processing' => 'processing',
            'payment_intent.canceled' => 'canceled',
            'payment_intent.requires_action' => 'requires_action',
        ];
        
        if (isset($statusMap[$status])) {
            Payment::where('payment_intent_id', $paymentIntentId)
                ->update(['status' => $statusMap[$status]]);
        }
    }

    public function getPaymentStatus(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $payment = Payment::where('payment_intent_id', $request->payment_intent_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'payment' => $payment,
        ]);
    }
    
    public function order(Request $request)
{
    try {
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }
        
        $history = DB::table('pay_submit')
            ->join('packages', 'packages.id', '=', 'pay_submit.package_id')
            ->where('pay_submit.user_id', $request->user_id)
            ->where('pay_submit.payment_status', 'success')
            ->select('*')
            ->orderBy('pay_submit.id', 'desc')
            ->get();
        
        // Transform the data to include full asset URLs
        $transformedHistory = $history->map(function($item) {
            // Convert to array if it's an object
            $itemArray = (array) $item;
            
            // Add full asset URLs for images
            if (!empty($item->package_banner_image)) {
                $itemArray['package_banner_image_url'] = asset($item->package_banner_image);
            }
            
            if (!empty($item->package_homepage_image)) {
                $itemArray['package_homepage_image_url'] = asset($item->package_homepage_image);
            }
            
            return $itemArray;
        });
        
        return response()->json([
            'status' => 200,
            'message' => 'Order history fetched successfully',
            'data' => $transformedHistory
        ], 200);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ], 500);
    }
}
}