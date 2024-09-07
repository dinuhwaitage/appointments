<?php

namespace App\Http\Controllers;

use App\Http\Resources\Patients\PatientDetailResource;
use App\Http\Resources\Patients\PatientListResource;
use App\Http\Resources\Patients\PatientSlimResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Http\Resources\Addresses\AddressDetailResource;
use App\Models\User;
use App\Models\Contact;
use App\Models\Address;
use App\Models\Patient;
use App\Models\Package;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{

    protected $contactController;

    // Inject the ContactController
    public function __construct(ContactController $contactController)
    {
        $this->contactController = $contactController;
    }

    public function generateRandomString($length = 5) {
        // Define the character set to use
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Shuffle the character set
        $characters = str_shuffle($characters);
        // Return the first $length characters of the shuffled set
        return substr($characters, 0, $length);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Build the query
        $query = Patient::query();
        $query->where('clinic_id', '=', Auth::user()->clinic_id);

        /* if(Auth::user()->contact->is_doctor() && Auth::user()->contact->employee){
            $doctor_id = Auth::user()->contact->employee->id;

            // Build the query
            $appointment = Appointment::query();
            $appointment->where('clinic_id', '=', Auth::user()->clinic_id);
            $appointment->where('doctor_id', '=', $doctor_id);
            $appointment->select('patient_id');
            $appointment_ids = $appointment->get();        
            $query->whereIn('id', $appointment_ids);
        } */

         // Get the filtered appointments
         $patients = $query->get();

        
        return PatientListResource::collection($patients);
    }

    /**
     * Display a listing of the slim resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function slim(Request $request)
    {
       
        $patients = Auth::user()->clinic->patients;
        return PatientSlimResource::collection($patients);
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
        if($request->has('contact') && $request->contact && optional($request->input('contact'))['email']){
            $request['email'] = $request->contact['email'];
        }else{
            // Generate a 5-character random string
            $name = ($request->contact['name']) ? $request->contact['name'] : $request->contact['first_name'];
            $name = str_replace(' ', '', $name);
            $request['email'] = $name.$this->generateRandomString(5)."@gmail.com";
        }
        $request['clinic_id'] = $clinic_id;

        // Validate the request
        $request->validate([
            'email' => 'email|unique:users,email,NULL,id,clinic_id,' . $clinic_id,
            'password' => 'string|min:8',
            'description' => 'string|max:255',
            'date_of_birth' => 'nullable|date',
            'registration_date'  => 'nullable|date',
            'package_start_date'  => 'nullable|date',
            'address' => 'array',
            'contact' => 'required|array'
        ]);
        $pass = $request->password ? $request->password : 'Test@1234';
        $user = User::create([
            'name' => $request->contact['name'],
            'email' => $request->email,
            'clinic_id' => $clinic_id,
            'password' => Hash::make($pass),
        ]);

        if ($request->has('contact')) {
            //Attach the contact to the employee
            $contact = new Contact($request->contact);
            $user->contact()->save($contact);

             // You can call the addRoleToContact method directly
             $this->contactController->addRoleToContact($user->contact, 'PATIENT');
        }

        $request['contact_id'] = $user->contact->id;

        $request['clinic_id'] = Auth::user()->clinic_id;

        if(!$request['registration_date']){
            $request['registration_date'] = date("Y-m-d");
        }
        // Create the patient
        $patient = Patient::create($request->only( ['description','date_of_birth','status','clinic_id','contact_id','gender','package_id','registration_date','package_start_date']));


        if ($request->has('address')) {
            // Attach the address to the patient
            $address = new Address($request->address);
            $address->clinic_id =  Auth::user()->clinic_id;
            $patient->address()->save($address);
        }
        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Auth::user()->clinic->patients->find($id);
        return new PatientDetailResource($patient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'description' => 'string|max:255',
            'address' => 'array',
            'contact' => 'array'
        ]);

        // Find the patient
        $patient = Auth::user()->clinic->patients->find($id);

        // Update patient details
        $patient->update($request->only( ['description','status','gender','date_of_birth','package_id','registration_date','package_start_date']));


        // Update address details if provided
        if ($request->has('address')) {
            $addressData = $request->address;
            
            if ($patient->address) {
                $patient->address->update($addressData);
            } else {
                $address = new Address($addressData);
                $address->clinic_id =  Auth::user()->clinic_id;
                $patient->address()->save($address);
            }
        }

        // Update address details if provided
        if ($request->has('contact')) {
            $contactData = $request->contact;
            if ($patient->contact) {
                $patient->contact->update($contactData);
            }
        }

        $deleteted = null;
        if($request->has('assets')){
            foreach($request->assets as $asset){
                if($asset['id'] && $asset['destroy']){
                    $deleteted = $this->file_delete($patient, $asset['id']);
                }
            }
        }

        return response()->json($patient, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // Find the patient by ID
         $patient = Auth::user()->clinic->patients->find($id);

         // Perform any necessary cleanup (e.g., deleting related records)
         // For example: $clinic->users()->delete(); if there are related users
 
         // Delete the patient
         $patient->delete();
 
         // Return a JSON response
         return response()->json(['message' => 'Patient deleted successfully'], 200);
    }

    public function file_delete($patient, $id)
    {
        // Find the attachment by ID
        $attachment = $patient->assets()->find($id);

        // Check if the attachment exists
        if($attachment && $attachment->id){
            
            // Delete the file from storage
            if(Storage::disk('public')->exists($attachment->url)) {
                Storage::disk('public')->delete($attachment->url);
            }

            // Delete the record from the database
            $attachment->delete();

            return true; //response()->json(['message' => 'Attachment deleted successfully'], 200);

        }else{
            return false; //return response()->json(['message' => 'Attachment not found'], 404);
        }
    }

    
     /**
     * Display a listing of the uploads resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploads(Request $request, $id)
    {
         // Find the patient
         $patient = Auth::user()->clinic->patients->find($id);
        if ($patient && $request->hasFile('assets')) {

            foreach ($request->file('assets') as $photo) {
                
                $photoPath = $photo->store('assets/'.$patient->clinic_id.'/'.$patient->id.'/patients', 'public');

                // Generate the URL for the uploaded file
                $url = Storage::url($photoPath);

                
                $file_name = $photo->getClientOriginalName(); // Create a unique filename
                $mime_type = $photo->getClientMimeType(); // Get the MIME type
                $file_size = $photo->getSize(); // Optionally, store the file size

                // Store the file in the 'public/room_photos' directory under a unique filename
                //$filePath = $file->storeAs('room_photos', $filename, 'public');

               $success =  $patient->assets()->create(['url' => $url, 'clinic_id' => $patient->clinic_id, 'file_name'=> $file_name, 'mime_type'=> $mime_type, 'file_size'=> $file_size]);
            }
            

            // Return a JSON response
            return response()->json($patient, 200);
            
        }else{
            return response()->json(['message' => 'Record not found'], 404);
        }
    }
}
