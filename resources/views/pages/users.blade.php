@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-6 py-10">
        <x-ui.form.form
            :runtime="$runtime"
            action="#"
            method="POST"
        />
    </div>
@endsection