<h3 class="font-semibold mb-2" id="{{$node['id']}}">
    {{ $node['label'] ?? '' }}
</h3>

<ol class="list-decimal list-inside space-y-1">
    @foreach($node['body'] ?? [] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ol>
