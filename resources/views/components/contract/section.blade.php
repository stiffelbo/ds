@php
    $headingTag = 'h' . min($level + 1, 6);
@endphp

<{{ $headingTag }} class="font-semibold mt-6 mb-3">
{{ $node['label'] ?? '' }}
</{{ $headingTag }}>

@if(!empty($node['body']))
    @foreach($node['body'] as $paragraph)
        <p class="mb-2 text-base-content/80">
            {{ $paragraph }}
        </p>
    @endforeach
@endif

@if(!empty($node['children']))
    <div class="pl-6 border-l border-base-300">
        @foreach($node['children'] as $child)
            @include('components.contract.node', [
                'node' => $child,
                'level' => $level + 1
            ])
        @endforeach
    </div>
@endif
