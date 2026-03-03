<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DataModelController
{
    public function show(Request $request): View
    {
        $path = base_path('/storage/DataModel.json');

        if (! File::exists($path)) {
            abort(404, 'DataModel.json not found.');
        }

        $raw = File::get($path);
        $tables = json_decode($raw, true);

        if(!$tables){
            abort(500, 'Tables data error');
        }

        return view('pages.data_model', [
            "tables" => $tables,
        ]);
    }
}
