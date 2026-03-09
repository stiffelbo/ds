<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ACLContractController extends Controller
{
    public function show(Request $request): View
    {
        $path = base_path('/storage/AclUiContract.json');

        if (! File::exists($path)) {
            abort(404, 'AclUiContract.json not found.');
        }

        $raw = File::get($path);
        $contract = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(500, 'Invalid AclUiContract.json format.');
        }

        return view('pages.acl_contract', [
            'contract' => $contract,
        ]);
    }
}
