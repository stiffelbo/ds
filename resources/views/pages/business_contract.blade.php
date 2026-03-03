{{-- resources/views/pages/contract.blade.php --}}

@extends('layouts.app')

@section('content')
    @php
        $meta = $contract['meta'] ?? [];
        $tree = $contract['tree'] ?? null;

        $contractName = data_get($meta, 'name', 'Business Contract');
        $contractVersion = $contract['contract_version'] ?? null;
        $language = data_get($meta, 'language', null);

        // Bezpieczne pobranie i normalizacja do tablic
        $sources = data_get($meta, 'sources', []);
        $sources = is_array($sources) ? $sources : [];

        $layers = data_get($meta, 'layers', []);
        $layers = is_array($layers) ? $layers : [];

        // Węzły meta budujemy tak, żeby dało się je wyrenderować istniejącymi komponentami
        $metaNodes = [];

        $metaNodes[] = [
            'id' => 'meta-summary',
            'type' => 'object',
            'label' => 'Meta',
            'body' => array_filter([
                'name' => $contractName,
                'contract_version' => $contractVersion,
                'language' => $language,
            ], fn ($v) => !is_null($v) && $v !== ''),
            'children' => [],
        ];


        if (!empty($sources)) {
            // sources w JSON to tablica obiektów: {id,label,color}
            $rows = [];
            foreach ($sources as $s) {
                if (!is_array($s)) continue;
                $rows[] = [
                    'id' => $s['id'] ?? '',
                    'label' => $s['label'] ?? '',
                    'color' => $s['color'] ?? '',
                ];
            }

            $metaNodes[] = [
                'id' => 'meta-sources',
                'type' => 'table',
                'label' => 'Źródła',
                'body' => $rows,
                'children' => [],
            ];
        }

        if (!empty($layers)) {
            // layers w JSON to tablica obiektów: {id,label,hint}
            $rows = [];
            foreach ($layers as $l) {
                if (!is_array($l)) continue;
                $rows[] = [
                    'id' => $l['id'] ?? '',
                    'label' => $l['label'] ?? '',
                    'hint' => $l['hint'] ?? '',
                ];
            }

            $metaNodes[] = [
                'id' => 'meta-layers',
                'type' => 'table',
                'label' => 'Warstwy',
                'body' => $rows,
                'children' => [],
            ];
        }
    @endphp

    <div class="container mx-auto px-6 py-10">

        <div class="mb-8">
            <h1 class="text-3xl font-bold">
                {{ $contractName }}
            </h1>

            @if($contractVersion)
                <p class="text-sm text-base-content/60 mt-2">
                    Wersja kontraktu: <span class="font-mono">{{ $contractVersion }}</span>
                </p>
            @endif
        </div>

        {{-- META: render przez istniejący renderer --}}
        <div class="mb-10">
            @foreach($metaNodes as $node)
                @include('components.contract.node', [
                    'node' => $node,
                    'level' => 1,
                    'visited' => [],
                    'maxDepth' => 20,
                ])
            @endforeach
        </div>

        {{-- TREE: render przez renderer z zabezpieczeniem --}}
        @if(is_array($tree) && !empty($tree))
            @include('components.contract.node', [
                'node' => $tree,
                'level' => 1,
                'visited' => [],
                'maxDepth' => 40,
            ])
        @else
            <div class="alert alert-info">
                <span>Brak drzewa kontraktu do wyświetlenia.</span>
            </div>
        @endif

    </div>
@endsection
