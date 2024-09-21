<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notes\NoteDetailResource;
use App\Http\Resources\Notes\NoteListResource;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Auth::user()->clinic->notes;
        return NoteListResource::collection($notes);
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

        $request['clinic_id'] = $clinic_id;
         // Validate the request
         $request->validate([
            'name' => 'required|string',
            'status' => 'nullable|string'
        ]);

        $request['status'] = $request['status']? $request['status'] : 'ACTIVE';
        // Create the note
        $note = Note::create($request->only( ['name', 'status','clinic_id']));

        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Auth::user()->clinic->notes->find($id);
        return new NoteDetailResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'string|max:255'
        ]);

        // Find the
        $note = Auth::user()->clinic->notes->find($id);

        // Update employee details
        $note->update($request->only( ['name', 'status']));

        return response()->json($note, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
         // Find the note by ID
         $note = Auth::user()->clinic->notes->find($id);
 
         // Delete the note
         $note->delete();
 
         // Return a JSON response
         return response()->json(['message' => 'Note deleted successfully'], 200);
    }

}
