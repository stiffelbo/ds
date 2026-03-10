@props([
    'node',
])

@php
    $name = $node['field'] ?? null;
    $value = $node['value'] ?? null;
    $error = $node['error'] ?? null;

    $variant = $node['variant'] ?? 'outlined';
    $size = $node['size'] ?? 'medium';

    $placeholder = $node['placeholder'] ?? null;
    $readonly = $node['readonly'] ?? false;
    $disabled = $node['disabled'] ?? false;
    $rows = $node['rows'] ?? 4;

    $sizeClasses = match($size) {
        'small' => 'px-3 py-2 text-sm',
        'large' => 'px-4 py-3 text-base',
        default => 'px-3 py-2.5 text-sm',
    };

    $variantClasses = match($variant) {
        'text' => 'border-transparent bg-transparent shadow-none focus:ring-0 focus:border-slate-300',
        'elevated' => 'border border-slate-200 bg-white shadow-sm focus:border-slate-400 focus:ring-slate-300',
        default => 'border border-slate-300 bg-white focus:border-slate-400 focus:ring-slate-300',
    };

    if ($error) {
        $variantClasses = 'border border-rose-400 bg-white focus:border-rose-500 focus:ring-rose-400';
    }

    $baseClasses = 'block w-full rounded-lg transition focus:outline-none focus:ring-2';
    $inputClasses = implode(' ', [
        $baseClasses,
        $variantClasses,
        $sizeClasses,
    ]);
@endphp

<x-ui.form.field-shell
    :node="$node"
    :error="$error"
>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @readonly($readonly)
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => $inputClasses,
        ]) }}
    >{{ $value }}</textarea>
</x-ui.form.field-shell>