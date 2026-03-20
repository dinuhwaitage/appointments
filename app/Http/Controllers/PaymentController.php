<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payments\PaymentDetailResource;
use App\Http\Resources\Payments\PaymentListResource;
use App\Models\Payment;
use App\Models\Subscription;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

 protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    /**
     * Create Razorpay Order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $subscription = Subscription::findOrFail($request->subscription_id);

            // Verify subscription belongs to user's clinic
            if ($subscription->clinic_id !== $user->clinic_id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Create order on Razorpay
            $orderData = [
                'receipt' => 'order_' . time(),
                'amount' => $request->amount * 100, // Amount in paise
                'currency' => 'INR',
                'customer_notify' => 1,
                'notes' => [
                    'subscription_id' => $subscription->id,
                    'clinic_id' => $user->clinic_id,
                    'user_id' => $user->id,
                ],
            ];

            $order = $this->razorpay->order->create($orderData);

            return response()->json([
                'order_id' => $order->id,
                'customer_name' => $request->customer_name ?? $user->name,
                'customer_email' => $request->customer_email ?? $user->email,
                'customer_phone' => $request->customer_phone ?? '',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create payment order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify Razorpay Payment
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
        ]);

        try {
            $user = Auth::user();
            $subscription = Subscription::findOrFail($request->subscription_id);

            // Verify subscription belongs to user's clinic
            if ($subscription->clinic_id !== $user->clinic_id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Verify signature
            $signatureVerification = $this->verifySignature(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            if (!$signatureVerification) {
                return response()->json([
                    'message' => 'Payment verification failed: Invalid signature',
                ], 400);
            }

            // Verify amount
            $payment = $this->razorpay->payment->fetch($request->razorpay_payment_id);
            if (($payment->amount / 100) != $request->amount) {
                return response()->json([
                    'message' => 'Payment verification failed: Amount mismatch',
                ], 400);
            }

            // Record payment in database
            $paymentRecord = Payment::create([
                'user_id' => $user->id,
                'clinic_id' => $user->clinic_id,
                'subscription_id' => $request->subscription_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'status' => 'completed',
                'transaction_id' => $request->razorpay_payment_id,
                'description' => $request->description ?? 'Online payment via Razorpay',
                'discount_amount' => $request->discount_amount ?? 0,
                'payment_type' => 'online',
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Payment verified and recorded successfully',
                'payment_id' => $paymentRecord->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment verification failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify Razorpay Signature
     */
    private function verifySignature($orderId, $paymentId, $signature)
    {
        $expectedSignature = hash_hmac(
            'sha256',
            "{$orderId}|{$paymentId}",
            config('services.razorpay.key_secret')
        );

        return hash_equals($expectedSignature, $signature);
    }
    private function authorizeAdminOrRoot()
    {
        $user = Auth::user();
        if (!$user || !$user->contact || (!optional($user->contact)->hasRole('ADMIN') && !optional($user->contact)->hasRole('ROOT'))) {
            return response()->json(['message' => 'User does not have permission to access payments.'], 422);
        }
        return null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($response = $this->authorizeAdminOrRoot()) {
            return $response;
        }
        $payments = Auth::user()->clinic->payments;
        return PaymentListResource::collection($payments);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizeAdminOrRoot()) {
            return $response;
        }

        // Validate the request
        $request->validate([
            'subscription_id' => 'required|integer', 
            'amount' => 'required|numeric',
            'status' => 'nullable|string', 
            'transaction_id' => 'nullable|string',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'payment_type' => 'nullable|in:manual,online'
       ]);

       $clinic_id = Auth::user()->clinic_id;
       $request['clinic_id'] = $clinic_id;
       $request['user_id'] = Auth::user()->id;

        // Create the payment
        $payment = Payment::create($request->only(['user_id', 'amount','status','description', 'clinic_id','payment_date','discount_amount','transaction_id','subscription_id','payment_type']));
        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($response = $this->authorizeAdminOrRoot()) {
            return $response;
        }

        $payment = Auth::user()->clinic->payments()->find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return new PaymentDetailResource($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizeAdminOrRoot()) {
            return $response;
        }

        // Validate the request
        $request->validate([
            'subscription_id' => 'nullable|integer', 
            'amount' => 'nullable|numeric',
            'status' => 'nullable|string', 
            'transaction_id' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'description' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'payment_type' => 'nullable|in:manual,online'
       ]);

         // Find the payment
         $payment = Auth::user()->clinic->payments->find($id);
         if (!$payment) {
             return response()->json(['message' => 'Payment not found'], 404);
         }

         // Update payment details
         $payment->update($request->only(['amount','status','description','payment_date','discount_amount','transaction_id','subscription_id','payment_type']));
         return response()->json($payment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($response = $this->authorizeAdminOrRoot()) {
            return $response;
        }

        // Find the payment by ID
        $payment = Auth::user()->clinic->payments->find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Delete the payment
        $payment->delete();

        // Return a JSON response
        return response()->json(['message' => 'Payment deleted successfully'], 204);
    }
}
