<?php

use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\PackageApiController;
use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('packages', PackageApiController::class)->names('api.packages');
Route::apiResource('bookings', BookingApiController::class)->names('api.bookings');
Route::apiResource('payments', PaymentApiController::class)
    ->only(['index','show','update','destroy'])
    ->names('api.payments');