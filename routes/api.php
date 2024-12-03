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
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\MasterMedicineController;
use App\Http\Controllers\PrescriptionController;
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
    Route::get('print_invoice/{id}', [InvoiceController::class, 'print_invoice']);

    Route::middleware('auth:sanctum')->group(function () {
       
        Route::post('/invoices/send_email', [InvoiceController::class, 'send_email']);
        Route::post('clinic_favicon_uploads/{id}', [ClinicController::class, 'upload_favicon']);
        Route::post('clinic_scanner_uploads/{id}', [ClinicController::class, 'upload_scanner']);
        Route::post('patient_uploads/{id}', [PatientController::class, 'uploads']);
        Route::post('appointment_uploads/{id}', [AppointmentController::class, 'uploads']);
        Route::post('clinic_logo_uploads/{id}', [ClinicController::class, 'upload_logo']);
        Route::get('/patients/slim', [PatientController::class, 'slim']);
        Route::get('/appointments/slim', [AppointmentController::class, 'slim']);
        Route::get('/employees/slim', [EmployeeController::class, 'slim']);
        Route::get('/doctors/slim', [DoctorController::class, 'slim']);
        Route::get('/invoices/stats', [InvoiceController::class, 'stats']);
        Route::get('/invoices/reports', [InvoiceController::class, 'reports']);
        Route::get('user', [UserController::class, 'user']);
        Route::patch('user_update/{id}', [ContactController::class, 'update']);
        Route::post('/users/admin_user', [UserController::class, 'admin_user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource('prescriptions', PrescriptionController::class);
        Route::apiResource('notes', NoteController::class);
        Route::apiResource('master_medicines', MasterMedicineController::class);
        Route::apiResource('medicines', MedicineController::class);
        Route::apiResource('clinics', ClinicController::class);
        Route::apiResource('packages', PackageController::class);
        Route::apiResource('invoices', InvoiceController::class);
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('doctors', DoctorController::class);
        Route::apiResource('patients', PatientController::class);
        Route::apiResource('appointments', AppointmentController::class);
    });

});

