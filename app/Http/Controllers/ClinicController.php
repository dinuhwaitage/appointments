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
use Illuminate\Support\Facades\Storage;

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
            $clinic->update($request->only( ['name', 'number','email', 'phone','description','status','registration_date','gst_number','website']));

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


    public function file_delete($clinic, $id)
    {
        // Find the attachment by ID
        $attachment = $clinic->logo()->find($id);

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

    public function upload_logo(Request $request, $id)
    {
    /*     $request->validate([
            'logo' => 'required|file|mimes:jpg,png,jpeg|max:2048',
        ]); */

         // Find the 
         $clinic = Auth::user()->clinic;

        if ($clinic && $request->hasFile('logo') && $request->file('logo')) {
            try {
                $photo = $request->file('logo');
                //$filename = time() . '_' . $photo->getClientOriginalName(); // Create a unique filename
                $photoPath = $photo->store('assets/'.$clinic->id.'/logo', 'public');

                // Store the file in the 'public/room_photos' directory under a unique filename
                //$filePath = $file->storeAs('room_photos', $filename, 'public');

                $file_name = $photo->getClientOriginalName(); // Create a unique filename
                $mime_type = $photo->getClientMimeType(); // Get the MIME type
                $file_size = $photo->getSize(); // Optionally, store the file size

                // Generate the URL for the uploaded file
                $url = Storage::url($photoPath);
                
                while($clinic->logo){
                    if($clinic->logo){
                        $deleteted = $this->file_delete($clinic, $clinic->logo->id);
                    }
                }

                $clinic->logo()->create(['url' => $url, 'clinic_id' => $clinic->id, 'file_name'=> $file_name, 'mime_type'=> $mime_type, 'file_size'=> $file_size]);
                // Return a JSON response
                return response()->json($clinic, 200);
            } catch (Exception $e) {
                // Catch any errors and return error message
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }

        }else{
            return response()->json(['message' => 'unable to upload logo'], 404);
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
