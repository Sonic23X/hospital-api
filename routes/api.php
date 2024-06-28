<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SaleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('products/stock/{id}', [ProductController::class, 'changeStock']);

    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('sales', SaleController::class);
});
