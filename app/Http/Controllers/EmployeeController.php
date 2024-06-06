<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employees\EmployeeDetailResource;
use App\Http\Resources\Employees\EmployeeListResource;
use App\Http\Resources\Contacts\ContactDetailResource;
use App\Http\Resources\Address\AddressDetailResource;
use App\Models\Employee;
use App\Models\Contact;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EmployeeListResource::collection(Employee::where('designation', '!=', 'DOCTOR')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->clinic_id = Auth::user()->clinic_id;
        // Create the employee
        $employee = Employee::create($request);

        if ($request->has('address')) {
            // Attach the address to the employee
            $address = new Address($request->address);
            $employee->address()->save($address);
        }

        if ($request->has('contact')) {
            // Attach the contact to the employee
            $contact = new Contact($request->contact);
            $employee->contact()->save($contact);
        }
        return response()->json($employee->load('address','contact'), 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return new EmployeeDetailResource(Employee::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
