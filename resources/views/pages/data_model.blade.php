@extends('layouts.app')

@section('content')
    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($tables as $table)
                @include('components.data_model.table-card', ['table' => $table])
            @endforeach
        </div>
    </div>
@endsection
