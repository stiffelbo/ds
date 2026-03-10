@props([
    'node',
])

@php
    $component = $node['component'] ?? null;
    $key = $node['key'] ?? null;
    $label = $node['label'] ?? null;
    $props = $node['props'] ?? [];
    $meta = $node['meta'] ?? [];

    $viewPath = $component
        ? 'components.ui.form.custom.' . $component
        : null;
@endphp

@if($component && view()->exists($viewPath))

    {{-- Render custom component --}}
    @include($viewPath, [
        'node' => $node,
        'props' => $props,
        'meta' => $meta,
    ])

@else

    {{-- Fallback debug renderer --}}
    <div class="col-span-12 rounded-box border border-dashed border-base-300 bg-base-200/40 px-4 py-5 text-sm text-base-content/70">
        <div class="flex items-center justify-between">
            <span class="font-medium text-base-content">
                Custom UI component
            </span>

            @if($component)
                <span class="font-mono text-xs text-base-content/50">
                    {{ $component }}
                </span>
            @endif
        </div>

        @if($label)
            <div class="mt-1 text-sm text-base-content/80">
                {{ $label }}
            </div>
        @endif

        <pre class="mt-3 overflow-x-auto rounded-box bg-neutral p-3 text-xs text-neutral-content">
{{ json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
        </pre>
    </div>

@endif