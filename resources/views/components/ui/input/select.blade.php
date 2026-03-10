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
        'small' => 'h-8 px-3 text-sm',
        'large' => 'h-12 px-4 text-base',
        default => 'h-10 px-3 text-sm',
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