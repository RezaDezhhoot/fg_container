<?php

use App\Http\Controllers\Api\v1\SendLicenseController;
use App\Http\Controllers\Api\v1\SendOtpController;
use App\Http\Controllers\Api\v1\SendReportsController;
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

Route::post('/v1/data', SendLicenseController::class);
Route::post('/v1/custom_data', [SendLicenseController::class,'sendByUserAndPass']);
Route::post('/v1/otp', SendOtpController::class);
Route::get('/v1/products', SendReportsController::class);
