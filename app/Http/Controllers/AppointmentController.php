<?php

namespace App\Http\Controllers;
use App\Http\Resources\Appointments\AppointmentDetailResource;
use App\Http\Resources\Appointments\AppointmentListResource;
use App\Http\Resources\Appointments\AppointmentSlimResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $patient_id = $request->input('patient_id');
        $doctor_id = $request->input('doctor_id');
        $appointments = Auth::user()->clinic->appointments;
     
        if($doctor_id){
            $appointments = $appointments->where('doctor_id', $doctor_id);
        }

        if($patient_id){
            $appointments = $appointments->where('patient_id', $patient_id);
        }
        return AppointmentListResource::collection($appointments);
    }

        /**
     * Display a listing of the slim resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function slim(Request $request)
    {
       
        $patient_id = $request->input('patient_id');
        $doctor_id = $request->input('doctor_id');
        $appointments = Auth::user()->clinic->appointments;
     
        if($doctor_id){
            $appointments = $appointments->where('doctor_id', $doctor_id);
        }

        if($patient_id){
            $appointments = $appointments->where('patient_id', $patient_id);
        }
        return AppointmentSlimResource::collection($appointments);
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
        $appointment = Appointment::create($request->only( ['details','date','time','patient_id','doctor_id', 'status','clinic_id','diagnosis','fee','package_id']));

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
            'details' => 'string|max:255',
            'date' => 'date',
        ]);

          // Find the 
          $appointment = Auth::user()->clinic->appointments->find($id);

          // Update employee details
          $appointment->update($request->only( ['date', 'time','details','status','doctor_id','diagnosis','fee','package_id']));

          return response()->json($appointment, 200);
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // Find the appointment by ID
         $appointment = Auth::user()->clinic->appointments->find($id);

         // Perform any necessary cleanup (e.g., deleting related records)
         // For example: $clinic->users()->delete(); if there are related users
 
         // Delete the appointment
         $appointment->delete();
 
         // Return a JSON response
         return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }

    private function uploads(Request $request, $id)
    {
         // Find the 
         $appointment = Auth::user()->clinic->appointments->find($id);

        if ($appointment && $request->hasFile('assets')) {
            foreach ($request->file('assets') as $photo) {
                
                //$filename = time() . '_' . $photo->getClientOriginalName(); // Create a unique filename
                $photoPath = $photo->store('assets/'.$appointment->clinic_id.'/'.$appointment->patient_id.'/appointments', 'public');

                // Store the file in the 'public/room_photos' directory under a unique filename
                //$filePath = $file->storeAs('room_photos', $filename, 'public');

                $success = $appointment->assets()->create(['url' => asset($photoPath), 'clinic_id' => $appointment->clinic_id]);
            }
            // Return a JSON response
            if($success){
                return response()->json(['message' => 'Appointment deleted successfully'], 200);
            }
        }else{
            return response()->json(['message' => 'unable to upload attachments'], 500);
        }
    }
}
