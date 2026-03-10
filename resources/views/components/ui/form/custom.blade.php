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
    <div class="col-span-12 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm text-slate-500">
        <div class="flex items-center justify-between">
            <span class="font-medium text-slate-600">
                Custom UI component
            </span>

            @if($component)
                <span class="font-mono text-xs text-slate-400">
                    {{ $component }}
                </span>
            @endif
        </div>

        @if($label)
            <div class="mt-1 text-sm text-slate-700">
                {{ $label }}
            </div>
        @endif

        <pre class="mt-3 overflow-x-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">
{{ json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
        </pre>
    </div>

@endif