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
        'text' => 'rounded-box px-1 py-1',
        'elevated' => 'card bg-base-100 shadow-md',
        default => 'card border border-base-300 bg-base-100 shadow-sm',
    };

    $contentClass = $variant === 'text'
        ? ''
        : 'card-body p-6';
@endphp

@if($collapsible)
    <details class="{{ $wrapperClass }}" @if(!$collapsed) open @endif>
        @if($label || $description)
            <summary class="cursor-pointer list-none select-none @if($variant !== 'text') {{ $contentClass }} @endif">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        @if($label)
                            <h2 class="text-lg font-semibold">
                                {{ $label }}
                            </h2>
                        @endif

                        @if($description)
                            <p class="mt-1 text-sm leading-6 text-base-content/70">
                                {{ $description }}
                            </p>
                        @endif
                    </div>

                    <span class="mt-1 text-xs font-medium uppercase tracking-wide text-base-content/50">
                        Toggle
                    </span>
                </div>
            </summary>
        @endif

        <div class="@if($variant !== 'text') px-6 pb-6 @endif">
            <div class="mt-6 grid grid-cols-12 gap-5">
                @foreach($children as $child)
                    <x-ui.form.node :node="$child" />
                @endforeach
            </div>
        </div>
    </details>
@else
    <section class="{{ $wrapperClass }}">
        <div class="{{ $contentClass }}">
            @if($label || $description)
                <div class="mb-6">
                    @if($label)
                        <h2 class="text-lg font-semibold">
                            {{ $label }}
                        </h2>
                    @endif

                    @if($description)
                        <p class="mt-1 text-sm leading-6 text-base-content/70">
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
        </div>
    </section>
@endif