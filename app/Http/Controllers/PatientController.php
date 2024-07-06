<?php

namespace App\Http\Controllers;

use App\Http\Resources\Patients\PatientDetailResource;
use App\Http\Resources\Patients\PatientListResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Http\Resources\Addresses\AddressDetailResource;

use App\Models\Contact;
use App\Models\Address;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PatientListResource::collection(Patient::all());
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
            'description' => 'string|max:255',
            'address' => 'array',
            'contact' => 'required|array'
        ]);


        $request['clinic_id'] = Auth::user()->clinic_id;
        // Create the patient
        $patient = Patient::create($request->only( ['description','status','clinic_id']));


        if ($request->has('contact')) {
            // Attach the contact to the patient
            $contact = new Contact($request->contact);
            $patient->contact()->save($contact);
        }

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
    public function show(Patient $patient)
    {
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
            'contact' => 'required|array'
        ]);

        // Find the employee
        $patient = Patient::findOrFail($id);

        // Update employee details
        $patient->update($request->only( ['description','status']));

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
            } else {
                $contact = new Contact($contactData);
                $patient->contact()->save($contact);
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
}
