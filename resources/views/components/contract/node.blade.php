@php
    $type = $node['type'] ?? 'section';
@endphp

<div class="mb-6">

    @switch($type)

        @case('section')
            @include('components.contract.section', compact('node','level'))
            @break

        @case('list')
            @include('components.contract.list', compact('node','level'))
            @break

        @case('ordered_list')
            @include('components.contract.ordered-list', compact('node','level'))
            @break

        @case('object')
            @include('components.contract.object', compact('node','level'))
            @break

        @case('table')
            @include('components.contract.table', compact('node','level'))
            @break

        @case('code')
            @include('components.contract.code', compact('node','level'))
            @break

        @case('tree')
            @include('components.contract.section', compact('node','level'))
            @break

        @default
            <pre class="bg-base-200 p-4 rounded">
                {{ json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
            </pre>

    @endswitch

</div>
