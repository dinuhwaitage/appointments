<?php

namespace App\Http\Controllers;


use App\Http\Resources\Subscriptions\SubscriptionDetailResource;
use App\Http\Resources\Subscriptions\SubscriptionListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Auth::user()->clinic->subscriptions;
        return SubscriptionListResource::collection($subscriptions);
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
           'plan_id' =>'integer', 
           'start_date' => 'date', 
           'end_date' => 'date', 
           'status' => 'string',
           'description' =>'nullable|string'
       ]);

       $clinic_id = Auth::user()->clinic_id;
       $request['clinic_id'] = $clinic_id;

        // Create the package
        $package = Package::create($request->only( ['name', 'amount','status','description', 'clinic_id','seating_count']));
        return response()->json($package, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscription = Auth::user()->clinic->subscriptions->find($id);
        return new SubscriptionDetailResource($subscription);
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
        'plan_id' =>'integer', 
        'start_date' => 'date', 
        'end_date' => 'date', 
        'status' => 'string',
        'description' =>'nullable|string'
        ]);

         // Find the 
         $subscription = Auth::user()->clinic->subscriptions->find($id);

         // Update employee details
         $subscription->update($request->only( ['clinic_id', 'plan_id', 'start_date', 'end_date', 'status','description']));
         return response()->json($subscription, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the subscription by ID
        $subscription = Auth::user()->clinic->subscriptions->find($id);

        // Delete the subscription
        $subscription->delete();

        // Return a JSON response
        return response()->json(['message' => 'Subscription deleted successfully'], 204);
    }
}
