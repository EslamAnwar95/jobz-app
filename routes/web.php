<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(2);
    return view('welcome');
});
