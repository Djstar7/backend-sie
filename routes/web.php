<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Routes CRUD pour les paiements
Route::resource('payments', PaymentController::class)->middleware(['auth']);
