<?php

use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contract', [ContractController::class, 'show'])
    ->name('contract.show');

Route::post('/ui/theme', function (Request $request) {
    $request->validate([
        'theme' => 'required|string',
    ]);

    session(['ui.theme' => $request->input('theme')]);

    return back();
})->name('ui.theme.set');
