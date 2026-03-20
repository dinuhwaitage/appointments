<?php

namespace App\Http\Controllers;


use App\Http\Resources\Subscriptions\SubscriptionDetailResource;
use App\Http\Resources\Subscriptions\SubscriptionListResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    private function authorizeRoot()
    {
        if (optional(Auth::user()->contact)->firstRole() !== 'ROOT') {
            return response()->json(['message' => 'User does not have permission to access subscriptions.'], 422);
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
        if ($response = $this->authorizeRoot()) {
            return $response;
        }
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
       if ($response = $this->authorizeRoot()) {
           return $response;
       }

        // Validate the request
        $request->validate([
           'plan_id' =>'required|integer', 
           'start_date' => 'required|date', 
           'end_date' => 'nullable|date', 
           'status' => 'nullable|string',
           'description' =>'nullable|string'
       ]);

       $clinic_id = Auth::user()->clinic_id;
       $data = $request->only(['plan_id', 'start_date', 'end_date', 'status', 'description']);
       $data['clinic_id'] = $clinic_id;

        // Create the subscription
        $subscription = Subscription::create($data);
        return response()->json($subscription, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($response = $this->authorizeRoot()) {
            return $response;
        }

        $subscription = Auth::user()->clinic->subscriptions()->find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
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
       if ($response = $this->authorizeRoot()) {
           return $response;
       }

       // Validate the request
       $request->validate([
        'plan_id' =>'integer', 
        'start_date' => 'date', 
        'end_date' => 'date', 
        'status' => 'string',
        'description' =>'nullable|string'
        ]);

         // Find the subscription
         $subscription = Auth::user()->clinic->subscriptions()->find($id);
         if (!$subscription) {
             return response()->json(['message' => 'Subscription not found'], 404);
         }

         // Update subscription details
         $subscription->update($request->only(['plan_id', 'start_date', 'end_date', 'status', 'description']));
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
        if ($response = $this->authorizeRoot()) {
            return $response;
        }

        // Find the subscription by ID
        $subscription = Auth::user()->clinic->subscriptions()->find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Delete the subscription
        $subscription->delete();

        // Return a JSON response
        return response()->json(['message' => 'Subscription deleted successfully'], 204);
    }
}
