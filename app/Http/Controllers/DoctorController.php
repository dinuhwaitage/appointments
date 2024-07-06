<?php

namespace App\Http\Controllers;

use App\Http\Resources\Doctors\DoctorDetailResource;
use App\Http\Resources\Doctors\DoctorListResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Http\Resources\Address\AddressDetailResource;
use App\Models\Employee;
use App\Models\User;
use App\Models\Contact;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Auth::user()->clinic->employees->where('designation', '=', 'DOCTOR');
        return DoctorListResource::collection($doctors);
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
        $request['email'] = $request->contact['email'];
        $request['designation'] = "DOCTOR";
        $request['clinic_id'] = $clinic_id;
         // Validate the request
         $request->validate([
            'email' => 'required|email|unique:users,email,NULL,id,clinic_id,' . $clinic_id,
            'password' => 'required|string|min:8',
            'code' => 'required|string|max:255',
            'address' => 'required|array',
            'contact' => 'required|array'
        ]);

        $user = User::create([
            'name' => $request->contact['first_name']." ".$request->contact['last_name'],
            'email' => $request->email,
            'clinic_id' => $clinic_id,
            'password' => Hash::make($request->password),
        ]);

        // Update user details
        $user->clinic_id = Auth::user()->clinic_id;
        $user->update($user->only( ['clinic_id']));

        if ($request->has('contact')) {
            //Attach the contact to the employee
            $contact = new Contact($request->contact);
            $user->contact()->save($contact);
        }

        $request['contact_id'] = $user->contact->id;

        // Create the employee
        $employee = Employee::create($request->only( ['code', 'date_of_birth','date_of_join', 'designation','qualification','status','clinic_id','contact_id']));

        if ($request->has('address')) {
            // Attach the address to the employee
            $address = new Address($request->address);
            $address->clinic_id =  Auth::user()->clinic_id;
            $employee->address()->save($address);
        }
        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Auth::user()->clinic->employees->find($id);
        return new DoctorDetailResource($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'code' => 'required|string|max:255',
            'address' => 'array',
            'contact' => 'array'
        ]);

        // Find the 
        $employee = Auth::user()->clinic->employees->find($id);

        // Update employee details
        $employee->update($request->only( ['code', 'date_of_birth','date_of_join', 'qualification','status']));

        // Update address details if provided
        if ($request->has('address')) {
            $addressData = $request->address;
            
            if ($employee->address) {
                $employee->address->update($addressData);
            } else {
                $address = new Address($addressData);
                $address->clinic_id =  Auth::user()->clinic_id;
                $employee->address()->save($address);
            }
        }

        // Update address details if provided
        if ($request->has('contact')) {
            $contactData = $request->contact;
            if ($employee->contact) {
                $employee->contact->update($contactData);
            } else {
                $contact = new Contact($contactData);
                $employee->contact()->save($contact);
            }
        }

        return response()->json($employee, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
         // Find the doctor by ID
         $doctor = Auth::user()->clinic->employees->where('designation', '=', 'DOCTOR')->find($id);

         // Perform any necessary cleanup (e.g., deleting related records)
         // For example: $clinic->users()->delete(); if there are related users
 
         // Delete the doctor
         $doctor->delete();
 
         // Return a JSON response
         return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }
}
