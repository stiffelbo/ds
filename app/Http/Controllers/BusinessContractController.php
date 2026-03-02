<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BusinessContractController
{
    public function show(Request $request): View
    {
        $path = base_path('BusinessContract.json');

        if (! File::exists($path)) {
            abort(404, 'BusinessContract.json not found.');
        }

        $raw = File::get($path);
        $contract = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(500, 'Invalid BusinessContract.json format.');
        }

        return view('pages.business_contract', [
            'contract' => $contract,
        ]);
    }
}
