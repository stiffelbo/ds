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

    /*
    |--------------------------------------------------------------------------
    | Size classes (daisyUI)
    |--------------------------------------------------------------------------
    */

    $sizeClasses = match($size) {
        'small' => 'textarea-sm',
        'large' => 'textarea-lg',
        default => '',
    };

    /*
    |--------------------------------------------------------------------------
    | Variant classes (mapped to daisyUI)
    |--------------------------------------------------------------------------
    */

    $variantClasses = match($variant) {
        'text' => 'textarea-ghost',
        'elevated' => 'textarea-bordered',
        default => 'textarea-bordered',
    };

    /*
    |--------------------------------------------------------------------------
    | Error override
    |--------------------------------------------------------------------------
    */

    if ($error) {
        $variantClasses .= ' textarea-error';
    }

    /*
    |--------------------------------------------------------------------------
    | Base classes
    |--------------------------------------------------------------------------
    */

    $baseClasses = 'textarea w-full';

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