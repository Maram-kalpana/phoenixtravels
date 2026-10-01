<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RazorpayService;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SubmitPayModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayController extends Controller
{
    protected $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    /**
     * Create a new order
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare notes - ensure they are properly formatted
            $transaction_id = 'TXN' . date('YmdHis') . strtoupper(Str::random(4));
            $notes = "Payment of amount Rs. ".$transaction_id;
            
            // Convert all note values to strings to avoid any type issues
            $formattedNotes = [];
            if (!empty($notes) && is_array($notes)) {
                foreach ($notes as $key => $value) {
                    $formattedNotes[$key] = (string) $value;
                }
            }

            // Create Razorpay order
            $razorpayOrder = $this->razorpay->createOrder(
                $request->amount,
                'INR',
                $transaction_id,
                $formattedNotes
            );

            // Store order in database
            $order = Order::create([
                'order_id' => $razorpayOrder['id'],
                'receipt' => $razorpayOrder['receipt'],
                'amount' => $razorpayOrder['amount'] / 100,
                'currency' => $razorpayOrder['currency'],
                'status' => $razorpayOrder['status'],
                'notes' => json_encode($razorpayOrder['notes'] ?? [])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $razorpayOrder['id'],
                    'amount' => $razorpayOrder['amount'] / 100,
                    'currency' => $razorpayOrder['currency'],
                    'key_id' => config('razorpay.key_id'),
                    'order_data' => $order
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment(Request $request){
        
        $validator = Validator::make($request->all(), [
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
         $submit_pay = SubmitPayModel::where('razorpay_order_id', $request->razorpay_order_id)->first();
        try {
            // Verify signature
            $verified = $this->razorpay->verifyPaymentSignature(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            if (!$verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment signature'
                ], 400);
            }

            // Find the order
            $order = Order::where('order_id', $request->razorpay_order_id)->first();
            
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Fetch payment details from Razorpay
            $paymentDetails = $this->razorpay->fetchPayment($request->razorpay_payment_id);
             
            // Store payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_intent_id' => $request->razorpay_payment_id,
                'amount' => $paymentDetails['amount'] / 100,
                'currency' => $paymentDetails['currency'],
                'status' => $paymentDetails['status'],
                'method' => $paymentDetails['method'],
                'payment_data' => json_encode($paymentDetails)
            ]);
            $bookingId = 'BK' . date('Ymd') . rand(1000, 9999);
            if($paymentDetails['status'] == 'captured'){
            $update_arr = array("mail" => $paymentDetails['email'],"phone" => $paymentDetails['contact'],"total_amount" => $paymentDetails['fee'],"booking_id" => $bookingId,"razorpay_payment_id" => $request->razorpay_payment_id,"payment_status" => 'success',"failure_reason" => '');    
            }else{
            $update_arr = array("mail" => $paymentDetails['email'],"phone" => $paymentDetails['contact'],"total_amount" => $paymentDetails['fee'],"booking_id" => $bookingId,"razorpay_payment_id" => $request->razorpay_payment_id,"payment_status" => 'success',"failure_reason" => json_encode($paymentDetails));
            }
            $affectedRows = SubmitPayModel::where("id", $submit_pay->id)->update($update_arr);
            // Update order status
            $order->status = 'paid';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'data' => [
                    'payment' => $payment,
                    'order' => $order
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            $update_arr = array("razorpay_payment_id" => $request->razorpay_payment_id,"payment_status" => 'failed',"failure_reason" => $e->getMessage());
            $affectedRows = SubmitPayModel::where("id", $submit_pay->id)->update($update_arr);
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch payment status
     */
    public function paymentStatus(Request $request, $paymentId)
    {
        try {
            $paymentDetails = $this->razorpay->fetchPayment($paymentId);
            
            return response()->json([
                'success' => true,
                'data' => $paymentDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $refund = $this->razorpay->refundPayment(
                $paymentId,
                $request->amount,
                $request->notes ?? []
            );

            // Update payment status in database
            Payment::where('payment_id', $paymentId)
                ->update(['status' => 'refunded']);

            return response()->json([
                'success' => true,
                'message' => 'Refund initiated successfully',
                'data' => $refund
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Refund failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook handler for Razorpay events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        try {
            $data = $this->razorpay->handleWebhook($payload, $signature);
            
            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}