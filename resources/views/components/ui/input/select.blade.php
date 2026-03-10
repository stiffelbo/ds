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
    $multiple = (bool) ($node['multiple'] ?? false);

    $options = data_get($node, 'options.items', []);
    $currentValues = $multiple
        ? array_map('strval', is_array($value) ? $value : (is_null($value) ? [] : [$value]))
        : [(string) $value];

    $sizeClasses = match($size) {
        'small' => 'select-sm',
        'large' => 'select-lg',
        default => '',
    };

    $variantClasses = match($variant) {
        'text' => 'select-ghost',
        'elevated' => 'select-bordered',
        default => 'select-bordered',
    };

    if ($error) {
        $variantClasses .= ' select-error';
    }

    $baseClasses = 'select w-full';
    $inputClasses = trim(implode(' ', [
        $baseClasses,
        $variantClasses,
        $sizeClasses,
    ]));

    $selectName = $multiple ? $name.'[]' : $name;
@endphp

<x-ui.form.field-shell
    :node="$node"
    :error="$error"
>
    <select
        name="{{ $selectName }}"
        id="{{ $name }}"
        @disabled($disabled || $readonly)
        @if($multiple) multiple @endif
        {{ $attributes->merge([
            'class' => $inputClasses,
        ]) }}
    >
        @if(!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $option)
            @php
                $optionValue = (string) ($option['value'] ?? '');
                $optionLabel = $option['label'] ?? $optionValue;
                $optionDisabled = (bool) ($option['disabled'] ?? false);
                $selected = in_array($optionValue, $currentValues, true);
            @endphp

            <option
                value="{{ $optionValue }}"
                @selected($selected)
                @disabled($optionDisabled)
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</x-ui.form.field-shell>