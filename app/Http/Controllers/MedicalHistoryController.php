<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MedicalHistories\MedicalHistoryListResource;
use App\Http\Resources\MedicalHistories\MedicalHistoryDetailResource;
use App\Models\MedicalHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MedicalHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         // Validate the request data
         $request->validate([
            'patient_id' => 'required|integer'
        ]);
        $medical_histories = Auth::user()->clinic->medical_histories->where('patient_id', '=', $request->patient_id);
        return MedicalHistoryListResource::collection($medical_histories);
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
            'patient_detail' => 'required|string',
            'patient_id' => 'nullable|integer',
            'family_detail' => 'nullable|string'
        ]);

        // Create the medical_history
        $medical_history = MedicalHistory::create($request->only( ['patient_detail', 'patient_start_date','family_detail','family_start_date','patient_id','clinic_id']));
        return response()->json(new MedicalHistoryDetailResource($medical_history), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
