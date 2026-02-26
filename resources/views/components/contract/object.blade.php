{{-- components/contract/object.blade.php --}}

<h3 class="font-semibold mb-3">
    {{ $node['label'] ?? '' }}
</h3>

<div class="overflow-x-auto">
    <table class="table table-zebra w-full">
        <tbody>
        @foreach($node['body'] ?? [] as $key => $value)
            <tr>
                <td class="font-mono text-sm w-1/3">{{ $key }}</td>
                <td>
                    @if(is_array($value))
                        <pre class="text-xs bg-base-200 p-2 rounded">
{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                        </pre>
                    @else
                        {{ $value }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
