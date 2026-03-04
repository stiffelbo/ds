{{-- resources/views/components/contract/entity.blade.php --}}
@php
    $body = is_array($node['body'] ?? null) ? $node['body'] : [];

    $source = is_array($body['source'] ?? null) ? $body['source'] : ['id' => 'ds', 'color' => '#16A34A'];
    $sourceId = strtoupper($source['id'] ?? 'DS');
    $sourceColor = $source['color'] ?? '#16A34A';

    $layer = strtoupper($body['layer'] ?? 'n/a');
    $purpose = $body['purpose'] ?? null;

    $nodeId = (string)($node['id'] ?? '');
    $label  = (string)($node['label'] ?? '');

    $structure = $body['structure_min'] ?? [];
    $structure = is_array($structure) ? $structure : [];

    // Indent (opcjonalnie)
    $lvl = (int)($level ?? 1);
    $indentClass = $lvl >= 3 ? 'ml-10' : ($lvl === 2 ? 'ml-6' : ($lvl === 1 ? 'ml-2' : ''));
@endphp

<div class="card bg-base-100 shadow border border-base-300 {{ $indentClass }}" id="{{$node['id']}}">
    <div class="relative">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 p-4">
            <div class="min-w-0">
                <h3 class="text-xl font-semibold leading-tight">
                    {{ $label }}
                </h3>

                @if(!empty($nodeId))
                    <div class="text-xs text-base-content/60 font-mono mt-1 break-all">
                        {{ $nodeId }}
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
        <span class="badge badge-outline text-xs">
            {{ $layer }}
        </span>

                <span class="badge text-white border-0 text-xs"
                      style="background-color: {{ $sourceColor }};">
            {{ $sourceId }}
        </span>
            </div>
        </div>

        <div class="card-body pl-6 gap-4">



            {{-- Purpose --}}
            @if(!empty($purpose))
                <div class="rounded-xl border border-base-300 bg-base-200/40 p-4">
                    <div class="text-xs uppercase tracking-wide text-base-content/60 mb-1">
                        Cel
                    </div>
                    <p class="text-sm text-base-content/90 leading-relaxed m-0">
                        {{ $purpose }}
                    </p>
                </div>
            @endif

            {{-- Optional: source_table_hint (często w raw z Optimy) --}}
            @if(!empty($body['source_table_hint']))
                <div class="rounded-xl border border-base-300 bg-base-200/20 p-4">
                    <div class="text-xs uppercase tracking-wide text-base-content/60 mb-1">
                        Źródło / tabela
                    </div>
                    <p class="text-sm text-base-content/90 leading-relaxed m-0">
                        {{ $body['source_table_hint'] }}
                    </p>
                </div>
            @endif

            {{-- Minimal structure --}}
            @if(count($structure))
                <div>
                    <div class="text-xs uppercase tracking-wide text-base-content/60 mb-2">
                        Minimalna struktura
                    </div>

                    <div class="rounded-xl border border-base-300 overflow-hidden">
                        <div class="grid grid-cols-1 divide-y divide-base-300">
                            @foreach($structure as $field => $type)
                                @php
                                    $isNested = is_array($type);
                                @endphp

                                <div class="p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="font-mono text-xs break-all">
                                            {{ $field }}
                                        </div>

                                        @if(!$isNested)
                                            <div class="font-mono text-xs text-base-content/70 text-right break-all">
                                                {{ (string)$type }}
                                            </div>
                                        @else
                                            <span class="badge badge-ghost text-xs">object</span>
                                        @endif
                                    </div>

                                    {{-- render zagnieżdżeń max 2 poziomy --}}
                                    @if($isNested)
                                        @php
                                            $nested = $type;
                                            $nested = is_array($nested) ? $nested : [];
                                            // limit na ilość pól, żeby nie zabić UI, gdyby ktoś wrzucił ogromny obiekt
                                            $nested = array_slice($nested, 0, 50, true);
                                        @endphp

                                        <div class="mt-2 pl-3 border-l border-base-300 space-y-2">
                                            @foreach($nested as $nField => $nType)
                                                @php $isNested2 = is_array($nType); @endphp

                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="font-mono text-xs break-all text-base-content/80">
                                                        {{ $field }}.{{ $nField }}
                                                    </div>

                                                    @if(!$isNested2)
                                                        <div class="font-mono text-xs text-base-content/60 text-right break-all">
                                                            {{ (string)$nType }}
                                                        </div>
                                                    @else
                                                        <span class="badge badge-ghost text-xs">object</span>
                                                    @endif
                                                </div>

                                                {{-- 3 poziomu już nie renderujemy (celowo), żeby nie było ciężko --}}
                                            @endforeach

                                            @if(count($type) > 50)
                                                <div class="text-xs text-base-content/50">
                                                    + pominięto {{ count($type) - 50 }} pól (limit renderu)
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
