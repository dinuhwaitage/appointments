<?php

namespace App\Http\Controllers;
use App\Http\Resources\Appointments\AppointmentDetailResource;
use App\Http\Resources\Appointments\AppointmentListResource;
use App\Http\Resources\Appointments\AppointmentSlimResource;
use App\Http\Resources\Appointments\AppointmentHistoryResource;
use App\Http\Resources\Appointments\AppointmentAssetsResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\AdditionalFee;
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
        // Validate the request data
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'days' => 'nullable|integer',
            'patient_id' => 'nullable|integer',
            'doctor_id' => 'nullable|integer',
            'history' => 'nullable',
            'assets' => 'nullable',
            'status' => 'nullable|string',
        ]);
       

        // Get the start and end date from the request
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');
        $days = $request->query('days');
        
       
        $patient_id = $request->input('patient_id');
        $doctor_id = $request->input('doctor_id');


       /*  if(Auth::user()->contact->is_doctor() && Auth::user()->contact->employee){
            $doctor_id = Auth::user()->contact->employee->id;
        } */

        if ($days) {
            $endDate = now();
            $startDate = now()->subDays($days);
        }

         // Build the query
         $query = Appointment::query();

         $query->where('clinic_id', '=', Auth::user()->clinic_id);
     
        if($doctor_id){
            $query->where('doctor_id', $doctor_id);
        }

        if($status){
            $query->where('status', $status);
        }

        if($patient_id){
            $query->where('patient_id', $patient_id);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
         // Get the filtered appointments
         $appointments = $query->get();

         if($request->query('history') == true){
            return AppointmentHistoryResource::collection($appointments);
         }else if($request->query('assets') == true){
            return AppointmentAssetsResource::collection($appointments);
         }else{
            return AppointmentListResource::collection($appointments);
         }
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

        $patient =  Auth::user()->clinic->patients->find($request['patient_id']);
        $request['clinic_id'] = Auth::user()->clinic_id;

        if($patient->package && $patient->package->seating_count){
            $available_count = intval($patient->available_count);
            if($patient->package->seating_count >= $available_count){
                $request['package_id'] = $patient->package->id;
                $request['seating_no'] = ($patient->package->seating_count - $available_count) + 1;
                $patient->available_count = $patient->package->seating_count - $request['seating_no'];
                $patient->save();
            }
            
        }
         
        // Create the appointment
        $appointment = Appointment::create($request->only( ['details','date','time','patient_id','doctor_id', 'status','clinic_id','diagnosis','fee','package_id','weight','height','seating_no','bp_detail']));

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
            'details' => 'nullable|string|max:255',
            'doctor_note' => 'nullable|string',
            'date' => 'nullable|date',
            'bp_detail' => 'nullable|string',
            'medical_history'  => 'nullable|string',
            'family_medical_history'  => 'nullable|string',
            'current_condition'  => 'nullable|string',
            'observation_details'  => 'nullable|string',
            'investigation_details'  => 'nullable|string',
            'treatment_plan'  => 'nullable|string',
            'procedures'  => 'nullable|string'
        ]);

          // Find the 
          $appointment = Auth::user()->clinic->appointments->find($id);

          //packages
          if($request['status'] == 'CANCLED' && $appointment->status != $request['status']){

            $patient =  Auth::user()->clinic->patients->find($appointment->patient_id);
            if($patient->package && $patient->package->id == $appointment->package_id && $patient->available_count){
                $patient->available_count = $patient->available_count + 1;
                $patient->save();
             }
        }

          // Update employee details
          $appointment->update($request->only( ['date', 'time','details','status','doctor_id','diagnosis','fee','package_id','doctor_note','weight','height','seating_no','bp_detail','medical_history','family_medical_history','current_condition','observation_details','investigation_details','treatment_plan','procedures']));
        if($request['medical_history'] || $request['family_medical_history']){
            $medical_history = optional($appointment->medical_history)->id ? $appointment->medical_history : new MedicalHistory();

                $medical_history->patient_detail = $request['medical_history'];
                $medical_history->family_detail = $request['family_medical_history'];
                $medical_history->appointment_id = $appointment->id;
                $medical_history->patient_id = $appointment->patient_id;
                $medical_history->clinic_id = $appointment->clinic_id;
                $appointment->medical_history()->save($medical_history);
        }
          if($request->has('assets')){
              foreach($request->assets as $asset){
                  if($asset && $asset['id'] && optional($asset)['destroy']){
                      $deleteted = $this->file_delete($appointment, $asset['id']);
                  }
              }
          }

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
         $patient = $appointment->patient;
         // Delete the appointment
         if($appointment->delete()){
            if($patient->package && $patient->package->seating_count){
                $available_count = intval($patient->available_count);
                if($patient->package->seating_count >= $available_count && $available_count > 0){
                    $patient->available_count = $available_count + 1;
                    $patient->save();
                }
                
            }
         }
 
         // Return a JSON response
         return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }


    public function file_delete($appointment, $id)
    {
        // Find the attachment by ID
        $attachment = $appointment->assets()->find($id);

        // Check if the attachment exists
        if($attachment && $attachment->id){
            // Delete the file from storage
            if (Storage::disk('public')->exists($attachment->url)) {
                Storage::disk('public')->delete($attachment->url);
            }

            // Delete the record from the database
            $attachment->delete();

            return true; //response()->json(['message' => 'Attachment deleted successfully'], 200);

        }else{
            return false; //return response()->json(['message' => 'Attachment not found'], 404);
        }
    }

    public function uploads(Request $request, $id)
    {
         // Find the 
         $appointment = Auth::user()->clinic->appointments->find($id);

        if ($appointment && $request->hasFile('assets')) {
            try {

            foreach ($request->file('assets') as $photo) {
                
                //$filename = time() . '_' . $photo->getClientOriginalName(); // Create a unique filename
                $photoPath = $photo->store('assets/'.$appointment->clinic_id.'/'.$appointment->patient_id.'/appointments', 'public');

                // Store the file in the 'public/room_photos' directory under a unique filename
                //$filePath = $file->storeAs('room_photos', $filename, 'public');

                $file_name = $photo->getClientOriginalName(); // Create a unique filename
                $mime_type = $photo->getClientMimeType(); // Get the MIME type
                $file_size = $photo->getSize(); // Optionally, store the file size

                // Generate the URL for the uploaded file
                $url = Storage::url($photoPath);

                $success = $appointment->assets()->create(['url' => $url, 'clinic_id' => $appointment->clinic_id, 'file_name'=> $file_name, 'mime_type'=> $mime_type, 'file_size'=> $file_size]);
            }

            // Return a JSON response
             return response()->json($appointment, 200);
        } catch (Exception $e) {
            // Catch any errors and return error message
            return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }
           
        }else{
            return response()->json(['message' => 'unable to upload attachments'], 404);
        }
    }

    //add additional charges
    public function additional_fee(Request $request, $appointment_id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'nullable|string'
        ]);
         // Find the 
         $appointment = Auth::user()->clinic->appointments->find($appointment_id);
         if($appointment){
        try {
        if ($appointment && $request->id) {
           
             $fee = $appointment->additional_fees()->find($request->id);
            
            // Update employee details
            $fee->update($request->only(['name','amount','description']));

            // Return a JSON response
             return response()->json($fee, 200);
        }else{
            $request['clinic_id'] = Auth::user()->clinic_id;
            
            // Update employee details
            $fee = $appointment->additional_fees()->create($request->only(['name','amount','description', 'clinic_id']));

            // Return a JSON response
             return response()->json($fee, 201);
        }
        } catch (Exception $e) {
            // Catch any errors and return error message
            return response()->json(['error' => 'unable to add/update additional fee: ' . $e->getMessage()], 500);
        }
    }else{
        return response()->json(['error' => 'patient not found '], 404);
    }
    }
}
