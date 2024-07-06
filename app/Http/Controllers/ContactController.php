<?php

namespace App\Http\Controllers;

use App\Http\Resources\Users\UserDetailResource;
use App\Models\Contact;
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
}