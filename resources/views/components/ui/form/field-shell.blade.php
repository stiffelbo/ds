@props([
    'node',
    'label' => null,
    'error' => null,
])

@php
    $field = $node['field'] ?? null;
    $resolvedLabel = $label ?? ($node['label'] ?? null);
    $helperText = $node['helper_text'] ?? null;
    $required = (bool) ($node['required'] ?? false);

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
    @if($resolvedLabel)
        <label for="{{ $field }}" class="mb-2 block text-sm font-medium text-slate-800">
            {{ $resolvedLabel }}

            @if($required)
                <span class="ml-1 text-rose-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if(!$error && $helperText)
        <p class="mt-2 text-sm leading-5 text-slate-500">
            {{ $helperText }}
        </p>
    @endif

    @if($error)
        <p class="mt-2 text-sm font-medium leading-5 text-rose-600">
            {{ $error }}
        </p>
    @endif
</div>