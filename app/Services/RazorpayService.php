<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $keyId;
    protected $keySecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->keyId = config('razorpay.key_id');
        $this->keySecret = config('razorpay.key_secret');
        $this->baseUrl = config('razorpay.api_url');
    }

    /**
     * Make API request to Razorpay using cURL
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        
        // Set Basic Authentication
        curl_setopt($ch, CURLOPT_USERPWD, $this->keyId . ':' . $this->keySecret);
        
        // Set headers
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Add data for POST/PUT requests
        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        // Execute cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($error) {
            Log::error('Razorpay cURL Error: ' . $error);
            throw new \Exception('Razorpay cURL Error: ' . $error);
        }
        
        // Check HTTP status code
        if ($httpCode < 200 || $httpCode >= 300) {
            Log::error('Razorpay API Error: HTTP ' . $httpCode . ' - ' . $response);
            throw new \Exception('Razorpay API Error: HTTP ' . $httpCode . ' - ' . $response);
        }
        
        return json_decode($response, true);
    }

    /**
     * Create an order
     */
    public function createOrder($amount, $currency = 'INR', $receipt = null, $notes = [])
    {
        // Ensure notes is a valid array/object
        $notesData = is_array($notes) ? $notes : [];
        
        // Remove any null values from notes
        $notesData = array_filter($notesData, function($value) {
            return !is_null($value);
        });

        $data = [
            'amount' => (int)($amount * 100), // Convert to paise and ensure integer
            'currency' => strtoupper($currency),
            'receipt' => $receipt ?? 'receipt_' . time(),
            'notes' => !empty($notesData) ? $notesData : (object)[] // Send empty object if no notes
        ];

        // Log the request data for debugging
        Log::info('Creating Razorpay order with data: ', $data);

        return $this->makeRequest('post', 'orders', $data);
    }

    /**
     * Fetch order details
     */
    public function fetchOrder($orderId)
    {
        return $this->makeRequest('get', 'orders/' . $orderId);
    }

    /**
     * Fetch all orders
     */
    public function fetchAllOrders($params = [])
    {
        $queryString = http_build_query($params);
        $endpoint = 'orders' . ($queryString ? '?' . $queryString : '');
        return $this->makeRequest('get', $endpoint);
    }

    /**
     * Fetch payment details
     */
    public function fetchPayment($paymentId)
    {
        return $this->makeRequest('get', 'payments/' . $paymentId);
    }

    /**
     * Capture payment
     */
    public function capturePayment($paymentId, $amount)
    {
        $data = [
            'amount' => (int)($amount * 100) // Convert to paise
        ];

        return $this->makeRequest('post', 'payments/' . $paymentId . '/capture', $data);
    }

    /**
     * Refund payment
     */
    public function refundPayment($paymentId, $amount = null, $notes = [])
    {
        $data = [
            'amount' => $amount ? (int)($amount * 100) : null,
            'notes' => !empty($notes) ? $notes : (object)[]
        ];

        // Remove null values
        $data = array_filter($data, function($value) {
            return !is_null($value);
        });

        return $this->makeRequest('post', 'payments/' . $paymentId . '/refund', $data);
    }

    /**
     * Create customer
     */
    public function createCustomer($name, $email, $contact, $notes = [])
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'contact' => $contact,
            'notes' => !empty($notes) ? $notes : (object)[]
        ];

        return $this->makeRequest('post', 'customers', $data);
    }

    /**
     * Verify payment signature
     */
    public function verifyPaymentSignature($orderId, $paymentId, $signature)
    {
        $generatedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->keySecret);
        
        return hash_equals($generatedSignature, $signature);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload, $signature)
    {
        // Verify webhook signature
        $expectedSignature = hash_hmac('sha256', $payload, config('razorpay.webhook_secret'));
        
        if (!hash_equals($expectedSignature, $signature)) {
            throw new \Exception('Invalid webhook signature');
        }

        $data = json_decode($payload, true);
        
        // Process different webhook events
        switch ($data['event']) {
            case 'payment.captured':
                $this->handlePaymentCaptured($data);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($data);
                break;
            case 'refund.created':
                $this->handleRefundCreated($data);
                break;
            // Add more events as needed
        }

        return $data;
    }

    private function handlePaymentCaptured($data)
    {
        // Implement your logic for payment captured
        Log::info('Payment captured: ' . json_encode($data));
    }

    private function handlePaymentFailed($data)
    {
        // Implement your logic for payment failed
        Log::info('Payment failed: ' . json_encode($data));
    }

    private function handleRefundCreated($data)
    {
        // Implement your logic for refund created
        Log::info('Refund created: ' . json_encode($data));
    }
}