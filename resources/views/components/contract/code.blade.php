{{-- components/contract/code.blade.php --}}

<h3 class="font-semibold mb-3">
    {{ $node['label'] ?? '' }}
</h3>

<pre class="bg-base-200 p-4 rounded overflow-x-auto text-sm">
{{ json_encode($node['body']['content'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
</pre>
