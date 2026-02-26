{{-- resources/views/pages/contract.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-10">

        <h1 class="text-3xl font-bold mb-8">
            {{ $contract['meta']['name'] ?? 'Development Contract' }}
        </h1>

        @if(isset($contract['tree']))
            @include('components.contract.node', [
                'node' => $contract['tree'],
                'level' => 1
            ])
        @endif

    </div>
@endsection
