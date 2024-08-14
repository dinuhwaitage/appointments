<?php

namespace App\Http\Controllers;

use App\Http\Resources\Packages\PackageDetailResource;
use App\Http\Resources\Packages\PackageListResource;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Auth::user()->clinic->packages;
        return PackageListResource::collection($packages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clinic_id = Auth::user()->clinic_id;
        $request['clinic_id'] = $clinic_id;
        // Validate the request
        $request->validate([
           'name' => 'string',
           'amount' => 'numeric'
       ]);

        // Create the package
        $package = Package::create($request->only( ['name', 'amount','status','description', 'clinic_id','seating_count']));
        return response()->json($package, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Auth::user()->clinic->packages->find($id);
        return new PackageDetailResource($package);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate the request
         $request->validate([
            'name' => 'string',
            'amount' => 'numeric'
        ]);

         // Find the 
         $package = Auth::user()->clinic->packages->find($id);

         // Update employee details
         $package->update($request->only( ['name', 'amount','status', 'description','status','gender','specification','seating_count']));
         return response()->json($package, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the package by ID
        $package = Auth::user()->clinic->packages->find($id);

        // Perform any necessary cleanup (e.g., deleting related records)
        // For example: $clinic->users()->delete(); if there are related users

        // Delete the package
        $package->delete();

        // Return a JSON response
        return response()->json(['message' => 'Package deleted successfully'], 200);

    }
}
