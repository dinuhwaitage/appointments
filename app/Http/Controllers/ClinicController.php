<?php

namespace App\Http\Controllers;

use App\Http\Resources\Clinics\ClinicDetailResource;
use App\Http\Resources\Clinics\ClinicListResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Http\Resources\Address\AddressDetailResource;
use App\Models\Clinic;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(optional(Auth::user()->contact)->firstRole() == 'ROOT'){
            return ClinicListResource::collection(Clinic::all());
        }else{
            return response()->json(['message' => 'User does not have permission to access clinics.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email'
        ]);
        if(optional(Auth::user()->contact)->firstRole() == 'ROOT'){
            $clinic = Clinic::create($request->only( ['name', 'number','email', 'phone','description','status','gst_number']));
            return response()->json(new ClinicDetailResource($clinic), 201);

        }else{
            return response()->json(['message' => 'User does not have permission to add clinics.'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clinic = Auth::user()->clinic;
        if($clinic->id == $id){
            return new ClinicDetailResource($clinic);
        }else{
            return response()->json(['message' => 'Clinic id does not match with the current user.'], 422);
        }
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
            'name' => 'string|max:255',
            'registration_date' => 'nullable|date',
            'address' => 'array'
        ]);

        if(optional(Auth::user()->contact)->firstRole() == 'ADMIN'){
            $clinic = Auth::user()->clinic;

            // Update clinic details
            $clinic->update($request->only( ['name', 'number','email', 'phone','description','status','registration_date','gst_number']));

            // Update address details if provided
            if ($request->has('address')) {
                $addressData = $request->address;
                
                if ($clinic->address) {
                    $clinic->address->update($addressData);
                } else {
                    $address = new Address($addressData);
                    $address->clinic_id =  Auth::user()->clinic_id;
                    $clinic->address()->save($address);
                }
            }
            return response()->json($clinic, 200);
        }else{
            return response()->json(['message' => 'User does not have permission to add clinics.'], 422);
        }

       
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
