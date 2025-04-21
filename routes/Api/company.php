<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Company\AuthController;
use App\Http\Controllers\Api\Company\JobController;

Route::prefix('company')->name('company.')->group(function () {


    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:company_api'])->group(function () {

        Route::apiResource('jobs', JobController::class);

    });
});
