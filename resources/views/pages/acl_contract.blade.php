
@extends('layouts.app')

@section('content')
    @php
        $meta = $contract['meta'] ?? [];
        $tree = $contract['tree'] ?? null;

        $contractName = data_get($meta, 'name', 'ACL Contract');
        $contractVersion = $contract['contract_version'] ?? null;
        $language = data_get($meta, 'language', null);

        // Bezpieczne pobranie i normalizacja do tablic
        $sources = data_get($meta, 'sources', []);
        $sources = is_array($sources) ? $sources : [];

        $layers = data_get($meta, 'layers', []);
        $layers = is_array($layers) ? $layers : [];

        $definitions = data_get($meta, 'definitions', []);
        $definitions = is_array($definitions) ? $definitions : [];

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

        if (!empty($definitions)) {
            $metaNodes[] = [
                'id' => 'meta-definitions',
                'type' => 'object',
                'label' => 'Definicje',
                'body' => $definitions,
                'children' => [],
            ];
        }

        if (!empty($sources)) {
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

        // Opcjonalny fallback, gdyby kiedyś root był "gołym node"
        if (!$tree && isset($contract['id'], $contract['label'], $contract['type'])) {
            $tree = $contract;
        }

        $toc = [];

        $buildToc = function(array $nodes, int $level = 1) use (&$toc, &$buildToc) {
            foreach ($nodes as $n) {
                if (!is_array($n)) continue;

                $type = $n['type'] ?? null;
                if ($type === 'void') {
                    // pomijamy w TOC i w renderze
                    continue;
                }

                $id = $n['id'] ?? null;
                $label = $n['label'] ?? null;

                if ($id && $label && $level < 3) {
                    $toc[] = [
                        'id' => $id,
                        'label' => $label,
                        'level' => $level,
                    ];
                }

                $children = $n['children'] ?? [];
                if (is_array($children) && !empty($children)) {
                    $buildToc($children, $level + 1);
                }
            }
        };

        if (is_array($tree)) {
            $buildToc($tree, 1);
        }
    @endphp

    <div class="mx-auto px-16 py-10">
        {{-- header jak masz --}}

        <div class="grid grid-cols-12 gap-4">
            {{-- SIDEBAR --}}
            <aside class="col-span-12 lg:col-span-3">
                <div class="sticky top-24">
                    <div class="text-sm font-semibold mb-3 text-base-content/70">
                        Spis treści
                    </div>

                    <nav class="max-h-[70vh] overflow-auto pr-2">
                        <ul class="space-y-1">
                            @foreach($toc as $item)
                                @php
                                    $indent = $item['level'] === 1 ? 'pl-0' : 'pl-4';
                                @endphp
                                <li>
                                    <a
                                        href="#{{ $item['id'] }}"
                                        data-toc-link="{{ $item['id'] }}"
                                        class="toc-link block rounded-md px-2 py-1.5 text-sm {{ $indent }} text-base-content/70 hover:text-base-content hover:bg-base-200"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </aside>

            {{-- CONTENT --}}
            <main class="col-span-12 lg:col-span-9">
                {{-- TREE --}}
                @if(is_array($tree) && !empty($tree))
                    <div class="space-y-10">
                        @foreach($tree as $node)
                            @include('components.contract.node', [
                                'node' => $node,
                                'level' => 1,
                                'visited' => [],
                                'maxDepth' => 40,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <span>Brak drzewa kontraktu do wyświetlenia.</span>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
