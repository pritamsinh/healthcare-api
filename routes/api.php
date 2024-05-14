<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HealthcareProfessionalController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'appointment'
], function ($router) {
    Route::post('/create', [AppointmentController::class, 'store'])->middleware('auth:api');
    Route::get('/list', [AppointmentController::class, 'list'])->middleware('auth:api');
    Route::get('/cancel/{id}', [AppointmentController::class, 'cancel'])->middleware('auth:api');
    Route::get('/complete/{id}', [AppointmentController::class, 'complete'])->middleware('auth:api');
    
});

Route::get('health-care-professionals', [HealthcareProfessionalController::class, 'index'])->middleware('auth:api');