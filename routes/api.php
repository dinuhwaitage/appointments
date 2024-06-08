<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('clinics', ClinicController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('patients', PatientController::class);
});

