<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::group(['middleware' => ['api']], function () {

    Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
        ->middleware(['throttle']); 
        
    include __DIR__ . "/Api/company.php";
    include __DIR__ . "/Api/candidate.php";

});