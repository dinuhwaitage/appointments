<?php

namespace App\Http\Controllers;
use App\Http\Resources\Prescriptions\PrescriptionResource;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         // Validate the request
         $request->validate([
            'appointment_id'  => 'required'
        ]);
        $prescriptions = Auth::user()->clinic->prescriptions->where('appointment_id', $request->appointment_id);
        
        return PrescriptionResource::collection($prescriptions);
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
            'dosages' => 'nullable|string',
            'duration' => 'nullable|string',
            'medicine' => 'nullable|string',
            'qty' => 'nullable|string',
            'notes' => 'nullable|string',
            'patient_id'  => 'required',
            'appointment_id'  => 'required'
        ]);
        $clinic_id = Auth::user()->clinic_id; 

        $request['clinic_id'] = $clinic_id;

        // Create the prescription
        $prescription = Prescription::create($request->only( ['medicine','dosages', 'duration','qty','notes','patient_id','appointment_id','clinic_id']));

        return response()->json($prescription, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prescription = Auth::user()->clinic->prescriptions->find($id);
        return new PrescriptionResource($prescription);
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
            'dosages' => 'nullable|string',
            'medicine' => 'nullable|string',
            'duration' => 'nullable|string',
            'qty' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

         // Find the prescription
        $prescription = Auth::user()->clinic->prescriptions->find($id);

        // Create the prescription
        $prescription->update($request->only( ['medicine','dosages', 'duration','qty','notes']));

        return response()->json($prescription, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the prescription by ID
        $prescription = Auth::user()->clinic->prescriptions->find($id);

        // Delete the prescription
        $prescription->delete();

        // Return a JSON response
        return response()->json(['message' => 'Prescription deleted successfully'], 200);
    }
}
