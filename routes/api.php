<?php

use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\HospitalizationController;
use App\Http\Controllers\Api\PatientController;

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
});
