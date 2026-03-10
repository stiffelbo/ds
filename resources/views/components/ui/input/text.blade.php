@props([
    'node',
])

@php
    $name = $node['field'] ?? null;
    $value = $node['value'] ?? null;
    $error = $node['error'] ?? null;

    $type = $node['type'] ?? 'text';

    $variant = $node['variant'] ?? 'outlined';
    $size = $node['size'] ?? 'medium';

    $placeholder = $node['placeholder'] ?? null;
    $readonly = $node['readonly'] ?? false;
    $disabled = $node['disabled'] ?? false;

    /*
    |--------------------------------------------------------------------------
    | Size classes
    |--------------------------------------------------------------------------
    */

    $sizeClasses = match($size) {
        'small' => 'h-8 px-3 text-sm',
        'large' => 'h-12 px-4 text-base',
        default => 'h-10 px-3 text-sm',
    };

    /*
    |--------------------------------------------------------------------------
    | Variant classes
    |--------------------------------------------------------------------------
    */

    $variantClasses = match($variant) {
        'text' => 'border-transparent bg-transparent shadow-none focus:ring-0 focus:border-slate-300',
        'elevated' => 'border border-slate-200 bg-white shadow-sm focus:border-slate-400 focus:ring-slate-300',
        default => 'border border-slate-300 bg-white focus:border-slate-400 focus:ring-slate-300',
    };

    /*
    |--------------------------------------------------------------------------
    | Error override
    |--------------------------------------------------------------------------
    */

    if ($error) {
        $variantClasses = 'border border-rose-400 bg-white focus:border-rose-500 focus:ring-rose-400';
    }

    /*
    |--------------------------------------------------------------------------
    | Base classes
    |--------------------------------------------------------------------------
    */

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
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @readonly($readonly)
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => $inputClasses,
        ]) }}
    />
</x-ui.form.field-shell>