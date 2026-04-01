<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrisonerController;
use App\Http\Controllers\VisitorController;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('prisoners', PrisonerController::class);
Route::resource('visitors', VisitorController::class);