<?php

use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\HospitalizationController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PatientRecordController;
use App\Http\Controllers\SpecialtyController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('products/stock/{id}', [ProductController::class, 'changeStock']);

    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('hospitalizations', HospitalizationController::class);
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('appointments/cancel/{appointment}', [AppointmentController::class, 'cancel']);
    Route::post('appointments/done/{appointment}', [AppointmentController::class, 'done']);
    Route::apiResource('doctors', DoctorController::class);
    Route::get('specialties', [SpecialtyController::class, 'index']);
    Route::post('/patients/{patientId}/records', [PatientRecordController::class, 'store']);
    Route::delete('/patients/records/{id}', [PatientRecordController::class, 'destroy']);
    Route::get('/patients/{patientId}/records', [PatientRecordController::class, 'index']);

    Route::get('invoices-client', [InvoiceController::class, 'indexClient']);
    Route::get('invoices-supplier', [InvoiceController::class, 'indexSupplier']);
    Route::get('invoice-client/{id}', [InvoiceController::class, 'showClient']);
    Route::get('invoice-supplier/{id}', [InvoiceController::class, 'showSupplier']);
    Route::get('invoices-cfdiuses', [InvoiceController::class, 'indexCfdiuses']);
    Route::get('invoices-regimes', [InvoiceController::class, 'indexRegimes']);
    Route::post('/invoice-client', [InvoiceController::class, 'storeClient']);
    Route::post('/invoice-supplier', [InvoiceController::class, 'storeSupplier']);
});
