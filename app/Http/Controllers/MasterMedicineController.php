<?php

namespace App\Http\Controllers;

use App\Http\Resources\MasterMedicines\MasterMedicineResource;
use App\Models\MasterMedicine;
use Illuminate\Http\Request;

class MasterMedicineController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // Validate the request
         $master_medicines = MasterMedicine::all();
        
        return MasterMedicineResource::collection($master_medicines);
    }
}
