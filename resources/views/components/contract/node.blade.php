{{-- components/contract/node.blade.php --}}

@php
    $node = is_array($node ?? null) ? $node : [];
    $type = $node['type'] ?? 'section';

    $visited = is_array($visited ?? null) ? $visited : [];
    $maxDepth = is_numeric($maxDepth ?? null) ? (int) $maxDepth : 10;

    $nodeId = $node['id'] ?? null;
    $hasCycle = $nodeId && in_array($nodeId, $visited, true);
    $tooDeep = ($level ?? 1) > $maxDepth;

    $nextVisited = $visited;
    if ($nodeId) $nextVisited[] = $nodeId;
@endphp

<div class="mb-6">

    @if($tooDeep)
        <div class="alert alert-warning">
            <span>Przerwano render: przekroczono limit głębokości ({{ $maxDepth }}).</span>
        </div>
    @elseif($hasCycle)
        <div class="alert alert-warning">
            <span>Przerwano render: wykryto cykl w drzewie (id: <span class="font-mono">{{ $nodeId }}</span>).</span>
        </div>
    @else
        @switch($type)

            @case('section')
                @include('components.contract.section', [
                    'node' => $node,
                    'level' => $level,
                    'visited' => $nextVisited ?? ($visited ?? []),
                    'maxDepth' => $maxDepth ?? 10,
                ])
                @break

            @case('list')
                @include('components.contract.list', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('rule')
                @include('components.contract.rule', [
                    'title' => $node['label'] ?? null,
                    'body' => $node['body'] ?? null,
                    'level' => $level,
                    'severity' => $node['severity'] ?? 'info',
                    'nodeId' => $node['id'] ?? null,
                ])
                @break

            @case('ordered_list')
                @include('components.contract.ordered-list', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('object')
                @include('components.contract.object', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('table')
                @include('components.contract.table', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('code')
                @include('components.contract.code', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('tree')
                {{-- "tree" traktujesz jak sekcję --}}
                @include('components.contract.section', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('entity')
                @include('components.contract.entity', ['node' => $node, 'level' => $level, 'visited' => $nextVisited, 'maxDepth' => $maxDepth])
                @break

            @case('void')
                @break;

            @default
                <pre class="bg-base-200 p-4 rounded overflow-x-auto text-sm">
{{ json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </pre>

        @endswitch
    @endif

</div>
