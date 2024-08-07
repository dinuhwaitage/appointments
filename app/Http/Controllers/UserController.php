<?php

namespace App\Http\Controllers;

use App\Http\Resources\Users\UserDetailResource;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\CustomException;
use App\Http\Controllers\ContactController;

class UserController extends Controller
{

    protected $contactController;

    // Inject the ContactController
    public function __construct(ContactController $contactController)
    {
        $this->contactController = $contactController;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        $user = Auth::user();
        return new UserDetailResource($user);
    }


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
    public function admin_user(Request $request)
    {
        if(Auth::user()->contact->firstRole() == 'ROOT'){
            $clinic_id = Auth::user()->clinic_id; 
            $request['email'] = $request->contact['email'];
            $request['clinic_id'] = $clinic_id;

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact' => 'required|array'
        ]);

        $user = User::create([
            'name' => $request->contact['first_name']." ".$request->contact['last_name'],
            'email' => $request->email,
            'clinic_id' => Auth::user()->clinic_id,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('contact')) {
            // Attach the contact to the employee
            $contact = new Contact($request->contact);
            $user->contact()->save($contact);

             // You can call the addRoleToContact method directly
             $this->contactController->addRoleToContact($user->contact, 'ADMIN');
        }

        return response()->json(['message' => 'User registered successfully'], 201);
    }

        return response()->json(['message' => 'User do not have permission to add user.'], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
