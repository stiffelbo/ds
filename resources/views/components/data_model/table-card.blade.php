@php
    $t = is_array($table ?? null) ? $table : [];

    $tableName = (string)($t['table_name'] ?? '');
    $label = (string)($t['label'] ?? $tableName);
    $desc = (string)($t['description'] ?? '');

    $layer = strtoupper((string)($t['layer'] ?? ''));
    $domain = strtoupper((string)($t['domain'] ?? ''));
    $status = strtoupper((string)($t['status'] ?? ''));
    $grain = (string)($t['grain'] ?? '');

    $pipelines = $t['pipeline'] ?? [];
    $pipelines = is_array($pipelines) ? $pipelines : [];

    $source = $t['source'] ?? [];
    $source = is_array($source) ? $source : [];
    $sourceId = strtoupper((string)($source['id'] ?? 'DS'));
    $sourceLabel = (string)($source['label'] ?? $sourceId);
    $sourceColor = (string)($source['color'] ?? '#16A34A');

    $relations = $t['relations'] ?? [];
    $relations = is_array($relations) ? $relations : [];

    $fields = $t['fields'] ?? [];
    $fields = is_array($fields) ? $fields : [];

    // limit UI
    $maxFields = is_numeric($maxFields ?? null) ? (int)$maxFields : 50;
    $maxRelations = is_numeric($maxRelations ?? null) ? (int)$maxRelations : 10;

    $fieldsLimited = array_slice($fields, 0, $maxFields);
    $relationsLimited = array_slice($relations, 0, $maxRelations);

    // proste mapowanie status -> badge class
    $statusBadge = match(strtolower($t['status'] ?? '')) {
        'mvp' => 'badge-success',
        'next' => 'badge-warning',
        'optional' => 'badge-ghost',
        'deprecated' => 'badge-error',
        default => 'badge-ghost',
    };

    $layerBadge = match(strtolower($t['layer'] ?? '')) {
        'fact' => 'badge-primary',
        'rules' => 'badge-accent',
        'raw' => 'badge-secondary',
        'dim' => 'badge-info',
        default => 'badge-ghost',
    };
@endphp

<div class="card bg-base-100 border border-base-300 shadow-sm w-full">
    <div class="card-body gap-4">

        {{-- Header --}}
        <div>
            <div class="text-lg font-semibold leading-tight truncate">
                {{ $label }}
            </div>
            <div class="text-xs text-base-content/60 font-mono break-all mt-1">
                {{ $tableName }}
            </div>
        </div>
        <div>
            <div class="flex items-center gap-2 flex-wrap justify-end">
                @if($layer)
                    <span class="badge {{ $layerBadge }} badge-outline text-xs" title="warstwa danych">
                        {{ $layer }}
                    </span>
                @endif

                @if($domain)
                    <span class="badge badge-outline text-xs" title="domena danych">
                        {{ $domain }}
                    </span>
                @endif

                @if($status)
                    <span class="badge {{ $statusBadge }} text-xs" title="status aplikacji modelu">
                        {{ $status }}
                    </span>
                @endif

                <span class="badge text-white border-0 text-xs"
                      style="background-color: {{ $sourceColor }};"
                      title="źródło danych"
                >
                    {{ $sourceId }}
                </span>
            </div>
        </div>

        {{-- Description --}}
        @if($desc)
            <div class="prose prose-sm max-w-none">
                <p class="m-0 text-sm text-base-content/80 leading-relaxed">
                    {{ $desc }}
                </p>
            </div>
        @endif

        {{-- Meta --}}
        <div class="gap-3">
            @if($grain)
                <div class="rounded-xl border border-base-300 bg-base-200/40 p-3" title="Co reprezentuje wiersz w tabeli">
                    <div class="text-xs uppercase tracking-wide text-base-content/60 mb-1">Grain</div>
                    <div class="text-sm text-base-content/80">{{ $grain }}</div>
                </div>
            @endif

            <div class="rounded-xl border border-base-300 bg-base-200/40 p-3">
                <div class="text-xs uppercase tracking-wide text-base-content/60 mb-1">Źródło</div>
                <div class="text-sm text-base-content/80">
                    <span class="font-semibold">{{ $sourceLabel }}</span>
                    <span class="text-xs text-base-content/60">({{ $sourceId }})</span>
                </div>
            </div>

            @if(count($pipelines))
                <div class="md:col-span-2 rounded-xl border border-base-300 bg-base-200/20 p-3">
                    <div class="text-xs uppercase tracking-wide text-base-content/60 mb-2">Pipeline</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($pipelines as $p)
                            <span class="badge badge-ghost text-xs">{{ (string)$p }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Fields table --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs uppercase tracking-wide text-base-content/60">
                    Pola
                </div>
                <div class="text-xs text-base-content/50">
                    {{ count($fields) }} pól
                    @if(count($fields) > $maxFields)
                        (pokazuję {{ $maxFields }})
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto border border-base-300 rounded-xl">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th class="w-1/3">Nazwa</th>
                        <th class="w-1/4">Typ</th>
                        <th>Opis</th>
                        <th class="w-24 text-right">Req</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($fieldsLimited as $f)
                        @php
                            $f = is_array($f) ? $f : [];
                            $fname = (string)($f['name'] ?? '');
                            $ftype = (string)($f['type'] ?? '');
                            $fdesc = (string)($f['description'] ?? '');
                            $freq = array_key_exists('required', $f) ? (bool)$f['required'] : true;
                        @endphp
                        <tr class="hover">
                            <td class="font-mono text-xs break-all">{{ $fname }}</td>
                            <td class="font-mono text-xs text-base-content/70 break-all">{{ $ftype }}</td>
                            <td class="text-xs text-base-content/70">{{ $fdesc }}</td>
                            <td class="text-right">
                                @if($freq)
                                    <span class="badge badge-outline badge-success text-xs">YES</span>
                                @else
                                    <span class="badge badge-outline text-xs">NO</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-sm text-base-content/60">
                                Brak zdefiniowanych pól.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Relations --}}
        @if(count($relations))
            <div class="rounded-xl border border-base-300 bg-base-200/20 p-3">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-xs uppercase tracking-wide text-base-content/60">
                        Relacje
                    </div>
                    <div class="text-xs text-base-content/50">
                        {{ count($relations) }} relacji
                        @if(count($relations) > $maxRelations)
                            (pokazuję {{ $maxRelations }})
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($relationsLimited as $r)
                        @php
                            $r = is_array($r) ? $r : [];
                            $rtype = (string)($r['type'] ?? '');
                            $rtbl = (string)($r['table'] ?? '');
                            $rfk = (string)($r['fk'] ?? '');
                            $rreq = array_key_exists('required', $r) ? (bool)$r['required'] : null;
                        @endphp

                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="badge badge-outline">{{ $rtype }}</span>
                            <span class="font-mono text-base-content/70">{{ $rfk }}</span>
                            <span class="text-base-content/50">→</span>
                            <span class="font-mono">{{ $rtbl }}</span>

                            @if($rreq === true)
                                <span class="badge badge-outline badge-success">required</span>
                            @elseif($rreq === false)
                                <span class="badge badge-outline">nullable</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Notes --}}
        @if(!empty($t['notes']))
            <div class="rounded-xl border border-base-300 bg-base-200/10 p-3">
                <div class="text-xs uppercase tracking-wide text-base-content/60 mb-1">Uwagi</div>
                <div class="text-sm text-base-content/80">{{ (string)$t['notes'] }}</div>
            </div>
        @endif

    </div>
</div>
