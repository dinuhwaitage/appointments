<?php

namespace App\Http\Controllers;

use App\Http\Resources\Plans\PlanDetailResource;
use App\Http\Resources\Plans\PlanListResource;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    private function authorizeRoot()
    {
        if (optional(Auth::user()->contact)->firstRole() !== 'ROOT') {
            return response()->json(['message' => 'User does not have permission to access plans.'], 422);
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
        $plans = Plan::all();
        return PlanListResource::collection($plans);
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
           'name' => 'string',
           'price' => 'numeric',
           'billing_cycle' =>'string',
           'description' => 'nullable|string',
           'status' => 'string',
       ]);

        // Create the plan
        $plan = Plan::create($request->only( ['name', 'price','status','description', 'billing_cycle']));
        return response()->json($plan, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($plan)
    {
        if ($response = $this->authorizeRoot()) {
            return $response;
        }

        return response()->json($plan);
        return  new PlanDetailResource($plan);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $plan)
    {
        if ($response = $this->authorizeRoot()) {
            return $response;
        }

         // Validate the request
         $request->validate([
            'name' => 'string',
            'price' => 'numeric',
            'billing_cycle' =>'string',
            'description' => 'nullable|string',
            'status' => 'string',
        ]);

         //Update plan details
         $plan->update($request->only( ['name', 'price','status', 'description','billing_cycle','billing_cycle']));
        return response()->json($plan, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($plan)
    {
        if ($response = $this->authorizeRoot()) {
            return $response;
        }

        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully'], 204);
    }
}
