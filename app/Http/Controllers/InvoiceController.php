<?php

namespace App\Http\Controllers;

use App\Http\Resources\Invoices\InvoiceDetailResource;
use App\Http\Resources\Invoices\InvoiceListResource;
use App\Http\Resources\Invoices\ReportResource;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $patient_id = $request->input('patient_id');
        $payment_date = $request->input('payment_date');
        $query = Invoice::query()
        ->where('invoices.clinic_id', Auth::user()->clinic_id);

        if($name){
            $query->join('patients', 'invoices.patient_id', "=", "patients.id")
            ->join('contacts', 'contacts.id', '=', 'patients.contact_id')
      
            ->where('contacts.first_name', 'like', '%'.$name.'%')
            ->orwhere('contacts.last_name', 'like', "%".$name."%")
            ;
        }

        if($payment_date){
            $query->where('invoices.payment_date', $payment_date);
       }

        if($patient_id){
             $query->where('invoices.patient_id', $patient_id);
        }
        return InvoiceListResource::collection($query->get());
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
        $rand = Str::random(16);
        $invoice->rnd_number = $invoice->id.".".$clinic_id.".".$rand;
        $invoice->save();

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

        if(!$invoice->rnd_number){
            $rand = Str::random(16);
            $invoice->rnd_number = $invoice->id.".".$clinic_id.".".$rand;
            $invoice->save();
        }

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

       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
         // Validate the request data
         $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'patient_id' => 'nullable|integer',
            'paid_by' => 'nullable|string',
        ]);
       

        // Get the start and end date from the request
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $patient_id = $request->query('patient_id');
        $paid_by = $request->query('paid_by');

        // Build the query
        $query = Invoice::query();

        $query->where('clinic_id', '=', Auth::user()->clinic_id);

        if($patient_id){
            $query->where('patient_id', '=', $patient_id);
        }

        if ($startDate) {
            $query->where('payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('payment_date', '<=', $endDate);
        }

        if ($paid_by) {
            $query->where('paid_by', '=', $paid_by);
        }

        // Get the filtered invoices
        $invoices = $query->get();
        return ReportResource::collection($invoices);
    }

    /**
     * Display a stats of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stats(Request $request)
    {
        // Validate the request data
        $request->validate([
            'days' => 'nullable|integer',
            // Add other filters if needed
        ]);
        $days = $request->query('days') ? $request->query('days') : 30;
        $endDate = now();
        $startDate = now()->subDays($days);
        

       // Build the query
       $query = Invoice::query();
       $patient_query = Patient::query();
       $appointment_query = Appointment::query();

       $query->where('clinic_id', '=', Auth::user()->clinic_id);
       $patient_query->where('clinic_id', '=', Auth::user()->clinic_id);
       $appointment_query->where('clinic_id', '=', Auth::user()->clinic_id);
       
        if ($startDate) {
            $query->where('payment_date', '>=', $startDate);
            $patient_query->where('created_at', '>=', $startDate);
            $appointment_query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('payment_date', '<=', $endDate);
            $patient_query->where('created_at', '<=', $endDate);
            $appointment_query->where('date', '<=', $endDate);
        };
        //$total_inv_query = $query;
        $cash_inv_query = clone  $query;
        $online_inv_query = clone  $query;
        $check_inv_query = clone  $query;

        $cash_inv_query->where('paid_by', '=', 'Cash');
        $online_inv_query->where('paid_by', '=', 'Online');
        $check_inv_query->where('paid_by', '=', 'Cheque');

        $cash_invoices = $cash_inv_query->get();
        $online_invoices = $online_inv_query->get();
        $check_invoices = $check_inv_query->get();

        // Get the filtered invoices
        $invoices = $query->get();
        $patients = $patient_query->get();
        $appointments = $appointment_query->get();

        // Generate the report (this can be customized as needed)
        $stats = [
            'total_appointments' => $appointments->count(),
            'total_patients' => $patients->count(),
            'total_invoices' => $invoices->count(),
            'total_cash_invoices' => $cash_invoices->sum('amount'),
            'total_online_invoices' => $online_invoices->sum('amount'),
            'total_check_invoices' => $check_invoices->sum('amount'),
            'total_amount' => $invoices->sum('amount')
        ];

        return response()->json($stats);
    }
}
