@props([
    'node',
])

@php
    $name = $node['field'] ?? null;
    $value = $node['value'] ?? false;
    $error = $node['error'] ?? null;

    $checked = filter_var($value, FILTER_VALIDATE_BOOL);
    $disabled = (bool) ($node['disabled'] ?? false);
    $readonly = (bool) ($node['readonly'] ?? false);
    $label = $node['label'] ?? null;
    $helperText = $node['helper_text'] ?? null;
    $required = (bool) ($node['required'] ?? false);

    $checkboxClasses = 'checkbox checkbox-sm mt-0.5';
    if ($error) {
        $checkboxClasses .= ' checkbox-error';
    }

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
@endphp

<div class="{{ implode(' ', $gridClasses) }}">
    <div class="rounded-box border border-base-300 bg-base-100 px-4 py-3 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="pt-0.5">
                <input type="hidden" name="{{ $name }}" value="0">

                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    value="1"
                    @checked($checked)
                    @disabled($disabled || $readonly)
                    {{ $attributes->merge([
                        'class' => $checkboxClasses,
                    ]) }}
                />
            </div>

            <div class="min-w-0 flex-1">
                @if($label)
                    <label for="{{ $name }}" class="label cursor-pointer justify-start gap-2 p-0">
                        <span class="label-text font-medium">
                            {{ $label }}
                        </span>

                        @if($required)
                            <span class="text-error">*</span>
                        @endif
                    </label>
                @endif

                @if(!$error && $helperText)
                    <div class="mt-1 text-sm text-base-content/70">
                        {{ $helperText }}
                    </div>
                @endif

                @if($error)
                    <div class="mt-1 text-sm text-error">
                        {{ $error }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>