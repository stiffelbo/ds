@props([
    'node',
])

@php
    $name = $node['field'] ?? null;
    $value = $node['value'] ?? null;
    $error = $node['error'] ?? null;

    $label = $node['label'] ?? null;
    $helperText = $node['helper_text'] ?? null;
    $required = (bool) ($node['required'] ?? false);
    $disabled = (bool) ($node['disabled'] ?? false);
    $readonly = (bool) ($node['readonly'] ?? false);

    $options = data_get($node, 'options.items', []);
    $currentValue = is_null($value) ? null : (string) $value;

    $xs = (int) ($node['xs'] ?? 12);
    $md = (int) ($node['md'] ?? 6);
    $xl = $node['xl'] ?? null;

    $gridClasses = [
        "col-span-{$xs}",
        "md:col-span-{$md}",
    ];

    if (!is_null($xl)) {
        $gridClasses[] = "xl:col-span-{$xl}";
    }

    $boxClasses = 'radio radio-sm';
    if ($error) {
        $boxClasses .= ' radio-error';
    }
@endphp

<div class="{{ implode(' ', $gridClasses) }}">
    <div class="rounded-box border border-base-300 bg-base-100 px-4 py-3">
        @if($label)
            <div class="mb-3 text-sm font-medium">
                {{ $label }}

                @if($required)
                    <span class="ml-1 text-error">*</span>
                @endif
            </div>
        @endif

        <div class="space-y-3">
            @foreach($options as $index => $option)
                @php
                    $optionValue = (string) ($option['value'] ?? '');
                    $optionLabel = $option['label'] ?? $optionValue;
                    $optionDisabled = (bool) ($option['disabled'] ?? false);
                    $optionId = $name . '_' . $index;
                @endphp

                <label for="{{ $optionId }}" class="label cursor-pointer justify-start gap-3 p-0">
                    <input
                        type="radio"
                        name="{{ $name }}"
                        id="{{ $optionId }}"
                        value="{{ $optionValue }}"
                        @checked($currentValue === $optionValue)
                        @disabled($disabled || $readonly || $optionDisabled)
                        {{ $attributes->class([$boxClasses]) }}
                    />

                    <span class="label-text text-sm">{{ $optionLabel }}</span>
                </label>
            @endforeach
        </div>

        @if(!$error && $helperText)
            <p class="mt-3 text-sm text-base-content/70">
                {{ $helperText }}
            </p>
        @endif

        @if($error)
            <p class="mt-3 text-sm font-medium text-error">
                {{ $error }}
            </p>
        @endif
    </div>
</div>