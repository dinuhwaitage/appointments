<?php

namespace App\Http\Controllers;
use App\Http\Resources\Appointments\AppointmentDetailResource;
use App\Http\Resources\Appointments\AppointmentListResource;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Auth::user()->clinic->appointments;
        return AppointmentListResource::collection($appointments);
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
            'details' => 'required|string|max:255',
            'date' => 'required|date',
            'patient_id' => 'required'
        ]);


        $request['clinic_id'] = Auth::user()->clinic_id;
        // Create the appointment
        $appointment = Appointment::create($request->only( ['details','date','time','patient_id','doctor_id', 'status','clinic_id']));

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Auth::user()->clinic->appointments->find($id);
        return new AppointmentDetailResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate the request
         $request->validate([
            'details' => 'required|string|max:255',
            'date' => 'required|date',
            'patient_id' => 'required'
        ]);

          // Find the 
          $appointment = Auth::user()->clinic->appointments->find($id);

          // Update employee details
          $appointment->update($request->only( ['date', 'time','details','status','doctor_id']));

          return response()->json($appointment, 200);
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
