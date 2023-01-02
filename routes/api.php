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

Route::prefix('v1')->group(function (){
    Route::post('/data', SendLicenseController::class);
    Route::post('/cart', \App\Http\Controllers\Api\v1\CartController::class);
    Route::post('/auth', \App\Http\Controllers\Api\v1\AuthController::class);
    Route::post('/custom_data', [SendLicenseController::class,'sendByUserAndPass']);
    Route::post('/otp', SendOtpController::class);
    Route::get('/products', SendReportsController::class);
    Route::get('/carts', [\App\Http\Controllers\Api\v1\PanelController::class,'index']);
    Route::get('/categories', [\App\Http\Controllers\Api\v1\CategoryController::class,'index']);
    Route::get('/categories-type', [\App\Http\Controllers\Api\v1\CategoryController::class,'getTypes']);
//    Route::put('/update-profile', [\App\Http\Controllers\Api\v1\PanelController::class,'update']);
});
