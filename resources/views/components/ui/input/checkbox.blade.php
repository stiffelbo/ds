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

    $boxClasses = 'h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-2 focus:ring-slate-400';
    if ($error) {
        $boxClasses = 'h-4 w-4 rounded border-rose-400 text-rose-600 focus:ring-2 focus:ring-rose-400';
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
    <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
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
                    'class' => $boxClasses,
                ]) }}
            />
        </div>

        <div class="min-w-0">
            @if($label)
                <label for="{{ $name }}" class="block text-sm font-medium text-slate-800">
                    {{ $label }}

                    @if($required)
                        <span class="ml-1 text-rose-500">*</span>
                    @endif
                </label>
            @endif

            @if(!$error && $helperText)
                <p class="mt-1 text-sm leading-5 text-slate-500">
                    {{ $helperText }}
                </p>
            @endif

            @if($error)
                <p class="mt-1 text-sm font-medium leading-5 text-rose-600">
                    {{ $error }}
                </p>
            @endif
        </div>
    </div>
</div>