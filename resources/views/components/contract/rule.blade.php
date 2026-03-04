@props([
    'title' => null,
    'body' => null,
    'level' => 1,
    'severity' => 'info', // info|success|warning|error
    'nodeId' => null,
])

@php
    $palette = [
        'info' => [
            'wrap' => 'border-sky-200 bg-sky-50 text-sky-950',
            'bar'  => 'bg-sky-500',
            'icon' => 'text-sky-600',
            'badge'=> 'bg-sky-100 text-sky-800 border-sky-200',
        ],
        'success' => [
            'wrap' => 'border-emerald-200 bg-emerald-50 text-emerald-950',
            'bar'  => 'bg-emerald-500',
            'icon' => 'text-emerald-600',
            'badge'=> 'bg-emerald-100 text-emerald-800 border-emerald-200',
        ],
        'warning' => [
            'wrap' => 'border-amber-200 bg-amber-50 text-amber-950',
            'bar'  => 'bg-amber-500',
            'icon' => 'text-amber-600',
            'badge'=> 'bg-amber-100 text-amber-900 border-amber-200',
        ],
        'error' => [
            'wrap' => 'border-rose-200 bg-rose-50 text-rose-950',
            'bar'  => 'bg-rose-500',
            'icon' => 'text-rose-600',
            'badge'=> 'bg-rose-100 text-rose-900 border-rose-200',
        ],
    ];

    $p = $palette[$severity] ?? $palette['info'];

    $severityLabel = [
        'info' => 'ZASADA',
        'success' => 'ZASADA',
        'warning' => 'ZASADA',
        'error' => 'ZASADA',
    ][$severity] ?? 'ZASADA';

    // Delikatne skalowanie nagłówka zależnie od głębokości
    $titleClass = $level <= 2 ? 'text-base' : 'text-sm';
@endphp

<div
    @if($nodeId) id="{{ $nodeId }}" @endif
class="relative overflow-hidden rounded-xl border {{ $p['wrap'] }} shadow-sm"
>
    <div class="absolute left-0 top-0 h-full w-1.5 {{ $p['bar'] }}"></div>

    <div class="flex gap-3 p-4 pl-5">
        {{-- Icon (Material-ish) --}}
        <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-lg bg-white/70 border border-black/5">
            <svg class="h-5 w-5 {{ $p['icon'] }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.935 2 3 5.935 3 10s3.935 8 9 8 9-3.935 9-8-3.935-8-9-8zm0 14c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6z"/>
            </svg>
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[11px] font-semibold tracking-wide {{ $p['badge'] }}">
                    {{ $severityLabel }}
                </span>

                @if($title)
                    <div class="font-semibold {{ $titleClass }}">
                        {{ $title }}
                    </div>
                @endif
            </div>

            @if($body)
                <div class="mt-2 text-sm leading-relaxed text-black/80">
                    @if(is_array($body))
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($body as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ $body }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
