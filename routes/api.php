<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\PassHandlingController;

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

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register','register');
    Route::post('/login','login');
    Route::post('/logout','logout');
    Route::post('/refresh','refresh');
});

Route::controller(VerifyEmailController::class)->group(function (){
    Route::post('/email/verify', 'verify');
    Route::post('/email/verification-notification', 'notification');
});

Route::controller(PassHandlingController::class)->group(function (){
    Route::post('/forgot','forgot');
    Route::post('/reset','reset');
    Route::post('/update','update');
    Route::post('/confirm','confirm');
});