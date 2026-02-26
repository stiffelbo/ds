{{-- components/contract/list.blade.php --}}

<h3 class="font-semibold mb-2">
    {{ $node['label'] ?? '' }}
</h3>

<ul class="list-disc list-inside space-y-1">
    @foreach($node['body'] ?? [] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

@if(!empty($node['children']))
    <div class="pl-6 border-l border-base-300 mt-4">
        @foreach($node['children'] as $child)
            @include('components.contract.node', [
                'node' => $child,
                'level' => $level + 1
            ])
        @endforeach
    </div>
@endif
