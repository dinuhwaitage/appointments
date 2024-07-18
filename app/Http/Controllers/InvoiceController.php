<?php

namespace App\Http\Controllers;

use App\Http\Resources\Invoices\InvoiceDetailResource;
use App\Http\Resources\Invoices\InvoiceListResource;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $patient_id = $request->input('patient_id');
        $invoices = Auth::user()->clinic->invoices;

        if($patient_id){
            $invoices = $invoices->where('patient_id', $patient_id);
        }

        return InvoiceListResource::collection($invoices);
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
           'payment_date' => 'date',
           'amount' => 'numeric'
       ]);

        // Create the invoice
        $invoice = Invoice::create($request->only( ['amount', 'payment_date','paid_by','transaction_number','description','status','clinic_id','patient_id']));
        return response()->json($invoice, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Auth::user()->clinic->invoices->find($id);
        return new InvoiceDetailResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate the request
         $request->validate([
            'payment_date' => 'date',
            'amount' => 'numeric'
        ]);

         // Find the 
         $invoice = Auth::user()->clinic->invoices->find($id);

         // Update invoice details
         $invoice->update($request->only( ['amount', 'payment_date','paid_by','transaction_number','description','status','clinic_id','patient_id']));
         return response()->json($invoice, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        // Find the invoice by ID
        $invoice = Auth::user()->clinic->invoices->find($id);

        // Perform any necessary cleanup (e.g., deleting related records)
        // For example: $clinic->users()->delete(); if there are related users

        // Delete the package
        $invoice->delete();

        // Return a JSON response
        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }
}
