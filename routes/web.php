<?php

use App\Http\Controllers\BusinessContractController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DataModelController;
use App\Http\Controllers\ACLContractController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contract', [ContractController::class, 'show'])
    ->name('contract.show');

Route::get('/business-contract', [BusinessContractController::class, 'show'])
    ->name('business-contract.show');

Route::get('/acl-contract', [ACLContractController::class, 'show'])
    ->name('acl-contract.show');

Route::get('/data-model', [DataModelController::class, 'show'])
    ->name('data-model.show');

Route::post('/ui/theme', function (Request $request) {
    $request->validate([
        'theme' => 'required|string',
    ]);

    session(['ui.theme' => $request->input('theme')]);

    return back();
})->name('ui.theme.set');



Route::get('/ui/users', function () {
    $ui = (new App\UI\UserUi())->toArray();

    $resolver = new App\UI\Runtime\FormRuntimeResolver();

    $runtime = $resolver->resolve(
        ui: $ui,
        formKey: 'add',
        record: null,
        values: old() ?: [],
        errors: session('errors')?->getBag('default')->toArray() ?? [],
        context: [
            'mode' => 'create',
        ],
    );

    return view('pages.users', [
        'runtime' => $runtime,
    ]);
});