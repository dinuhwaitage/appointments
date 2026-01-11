<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payments\PaymentDetailResource;
use App\Http\Resources\Payments\PaymentListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Payment::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'subscription_id'=>'integer', 
            'status'=>'string', 
            'transaction_id'=>'string',
            'payment_date'=>'date',
            'description'=>'nullable|string',
            'amount' => 'numeric',
            'status' => 'nullable|string',
            'discount_amount' =>'nullable|numeric',
            'transaction_id' =>'nullable|string',
            'subscription_id' =>'integer'
       ]);

       $clinic_id = Auth::user()->clinic_id;
       $request['clinic_id'] = $clinic_id;
       $request['user_id'] = Auth::user()->id;

        // Create the package
        $payment = Payment::create($request->only(['user_id', 'amount','status','description', 'clinic_id','payment_date','discount_amount','transaction_id','subscription_id']));
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
        $payment = Auth::user()->clinic->payments->find($id);
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
        // Validate the request
        $request->validate([
            'subscription_id'=>'integer', 
            'status'=>'string', 
            'transaction_id'=>'string',
            'payment_date'=>'date',
            'description'=>'nullable|string',
            'amount' => 'numeric',
            'status' => 'nullable|string',
            'discount_amount' =>'nullable|numeric',
            'transaction_id' =>'nullable|string',
            'subscription_id' =>'integer'
       ]);

         // Find the 
         $payment = Auth::user()->clinic->payments->find($id);

         // Update payment details
         $payment->update($request->only( ['user_id', 'amount','status','description','payment_date','discount_amount','transaction_id','subscription_id']));
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
        // Find the payment by ID
        $payment = Auth::user()->clinic->payments->find($id);

        // Delete the payment
        $payment->delete();

        // Return a JSON response
        return response()->json(['message' => 'Payment deleted successfully'], 204);
    }
}
