<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Candidate\AuthController;

use App\Http\Controllers\Api\Candidate\JobApplicationController;

Route::prefix('candidate')->name('candidate.')->group(function () {

    // 🟡 Auth Routes
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    // // 🔴 Protected Routes (Authenticated Candidates Only)
        
    Route::middleware(['auth:candidate_api'])->group(function () {
        Route::post('jobs/{id}/apply', [JobApplicationController::class, 'apply']);
    });
});