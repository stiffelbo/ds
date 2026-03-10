@props([
    'node',
])

@php
    $label = $node['label'] ?? null;
    $description = $node['description'] ?? null;
    $children = is_array($node['children'] ?? null) ? $node['children'] : [];
    $collapsible = (bool) ($node['collapsible'] ?? false);
    $collapsed = (bool) ($node['collapsed'] ?? false);
    $variant = $node['variant'] ?? 'outlined';

    $wrapperClass = match($variant) {
        'text' => 'rounded-2xl px-1 py-1',
        'elevated' => 'rounded-2xl bg-white p-6 shadow-md ring-1 ring-slate-200',
        default => 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm',
    };
@endphp

@if($collapsible)
    <details class="{{ $wrapperClass }}" @if(!$collapsed) open @endif>
        @if($label || $description)
            <summary class="cursor-pointer list-none select-none">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        @if($label)
                            <h2 class="text-lg font-semibold text-slate-900">
                                {{ $label }}
                            </h2>
                        @endif

                        @if($description)
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                {{ $description }}
                            </p>
                        @endif
                    </div>

                    <span class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                        Toggle
                    </span>
                </div>
            </summary>
        @endif

        <div class="mt-6 grid grid-cols-12 gap-5">
            @foreach($children as $child)
                <x-ui.form.node :node="$child" />
            @endforeach
        </div>
    </details>
@else
    <section class="{{ $wrapperClass }}">
        @if($label || $description)
            <div class="mb-6">
                @if($label)
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $label }}
                    </h2>
                @endif

                @if($description)
                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        {{ $description }}
                    </p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-12 gap-5">
            @foreach($children as $child)
                <x-ui.form.node :node="$child" />
            @endforeach
        </div>
    </section>
@endif