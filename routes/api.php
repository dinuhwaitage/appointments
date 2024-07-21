<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\InvoiceController;
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

Route::middleware(['whitelist.host'])->group(function () {
    
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/invoices/reports', [InvoiceController::class, 'reports']);
        Route::get('user', [UserController::class, 'user']);
        Route::patch('user_update/{id}', [ContactController::class, 'update']);
        Route::post('/users/admin_user', [UserController::class, 'admin_user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource('clinics', ClinicController::class);
        Route::apiResource('packages', PackageController::class);
        Route::apiResource('invoices', InvoiceController::class);
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('doctors', DoctorController::class);
        Route::apiResource('patients', PatientController::class);
        Route::apiResource('appointments', AppointmentController::class);
    });

});

