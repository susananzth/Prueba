<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('employees');
});

Route::get('/employees-view', function () {
    return view('employees');
});
