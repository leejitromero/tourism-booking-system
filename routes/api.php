<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\PackageApiController;
use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::apiResource('packages', PackageApiController::class)->names('api.packages');
Route::apiResource('bookings', BookingApiController::class)->names('api.bookings');
Route::apiResource('payments', PaymentApiController::class)
    ->only(['index', 'show', 'update', 'destroy'])
    ->names('api.payments');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
});