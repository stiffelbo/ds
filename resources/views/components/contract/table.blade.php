{{-- components/contract/table.blade.php --}}

<h3 class="font-semibold mb-3">
    {{ $node['label'] ?? '' }}
</h3>

@php
    $rows = $node['body'] ?? [];
    $headers = !empty($rows) ? array_keys($rows[0]) : [];
@endphp

<div class="overflow-x-auto">
    <table class="table table-zebra w-full">
        <thead>
        <tr>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($headers as $header)
                    <td>{{ $row[$header] ?? '' }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
