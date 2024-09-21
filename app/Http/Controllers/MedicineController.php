<?php

namespace App\Http\Controllers;

use App\Http\Resources\Medicines\MedicineDetailResource;
use App\Http\Resources\Medicines\MedicineListResource;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicines = Auth::user()->clinic->medicines;
        return MedicineListResource::collection($medicines);
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
            'name' => 'required|string',
            'status' => 'nullable|string'
        ]);

        $request['status'] = $request['status']? $request['status'] : 'ACTIVE';
        // Create the medicine
        $medicine = Medicine::create($request->only( ['name', 'status','clinic_id']));

        return response()->json($medicine, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicine = Auth::user()->clinic->medicines->find($id);
        return new MedicineDetailResource($medicine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'string|max:255'
        ]);

        // Find the
        $medicine = Auth::user()->clinic->medicines->find($id);

        // Update employee details
        $medicine->update($request->only( ['name', 'status']));

        return response()->json($medicine, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
         // Find the medicine by ID
         $medicine = Auth::user()->clinic->medicines->find($id);
 
         // Delete the medicine
         $medicine->delete();
 
         // Return a JSON response
         return response()->json(['message' => 'Medicine deleted successfully'], 200);
    }

}
