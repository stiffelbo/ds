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

    $boxClasses = 'h-4 w-4 border-slate-300 text-slate-900 focus:ring-2 focus:ring-slate-400';
    if ($error) {
        $boxClasses = 'h-4 w-4 border-rose-400 text-rose-600 focus:ring-2 focus:ring-rose-400';
    }
@endphp

<div class="{{ implode(' ', $gridClasses) }}">
    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        @if($label)
            <div class="mb-3 text-sm font-medium text-slate-800">
                {{ $label }}

                @if($required)
                    <span class="ml-1 text-rose-500">*</span>
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

                <label for="{{ $optionId }}" class="flex items-center gap-3 text-sm text-slate-700">
                    <input
                        type="radio"
                        name="{{ $name }}"
                        id="{{ $optionId }}"
                        value="{{ $optionValue }}"
                        @checked($currentValue === $optionValue)
                        @disabled($disabled || $readonly || $optionDisabled)
                        {{ $attributes->class([$boxClasses]) }}
                    />

                    <span>{{ $optionLabel }}</span>
                </label>
            @endforeach
        </div>

        @if(!$error && $helperText)
            <p class="mt-3 text-sm leading-5 text-slate-500">
                {{ $helperText }}
            </p>
        @endif

        @if($error)
            <p class="mt-3 text-sm font-medium leading-5 text-rose-600">
                {{ $error }}
            </p>
        @endif
    </div>
</div>