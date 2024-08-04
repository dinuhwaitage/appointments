<?php

namespace App\Http\Controllers;

use App\Http\Resources\Users\UserDetailResource;
use App\Models\Contact;
use App\Models\Role;
use App\Models\ContactRole;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $current_user = Auth::user();
        if($current_user->contact->id != $id){
            throw new CustomException('Not a valid user.');
        }

        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'mobile' => 'string|max:15',
            'status' => 'string|max:7',
        ]);

         // Update user details
         $current_user->contact->update($request->only( ['status','first_name','last_name','mobile']));

         if( $current_user->contact->employee){
             // Update employee details
            $current_user->contact->employee->update($request->only( ['date_of_birth','gender']));

            if($current_user->contact->employee->address){
                // Update address details
                $current_user->contact->employee->address->update($request->only( ['city','line1', 'zipcode']));
            }
         }

         if( $current_user->contact->patient){
            // Update patient details
           $current_user->contact->patient->update($request->only( ['date_of_birth','gender']));

           if($current_user->contact->patient->address){
            // Update address details
            $current_user->contact->patient->address->update($request->only( ['city','line1', 'zipcode']));
        }
        }
         return response()->json(new UserDetailResource($current_user), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }

       /**
     * Attach a role to a contact.
     *
     * @param model $contact
     * @param name $role_name
     * @return \Illuminate\Http\Response
     */
    public function addRoleToContact($contact, $role_name)
    {
        //fine role by name
        $role = Role::where('name', $role_name)->get()->first();
         // Attach the role to the contact
         $contact->roles()->attach($role);
    }
}
