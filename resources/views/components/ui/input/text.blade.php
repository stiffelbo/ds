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
    | Size classes (daisyUI)
    |--------------------------------------------------------------------------
    */

    $sizeClasses = match($size) {
        'small' => 'input-sm',
        'large' => 'input-lg',
        default => '',
    };

    /*
    |--------------------------------------------------------------------------
    | Variant classes (mapped to daisyUI styles)
    |--------------------------------------------------------------------------
    */

    $variantClasses = match($variant) {
        'text' => 'input-ghost',
        'elevated' => 'input-bordered',
        default => 'input-bordered',
    };

    /*
    |--------------------------------------------------------------------------
    | Error override
    |--------------------------------------------------------------------------
    */

    if ($error) {
        $variantClasses .= ' input-error';
    }

    /*
    |--------------------------------------------------------------------------
    | Base classes
    |--------------------------------------------------------------------------
    */

    $baseClasses = 'input w-full';

    $inputClasses = trim(implode(' ', [
        $baseClasses,
        $variantClasses,
        $sizeClasses,
    ]));
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