<?php

use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contract', [ContractController::class, 'show'])
    ->name('contract.show');
