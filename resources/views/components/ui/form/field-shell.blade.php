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
        <label for="{{ $field }}" class="label p-0 pb-2">
            <span class="label-text text-sm font-medium">
                {{ $resolvedLabel }}

                @if($required)
                    <span class="ml-1 text-error">*</span>
                @endif
            </span>
        </label>
    @endif

    {{ $slot }}

    @if(!$error && $helperText)
        <p class="mt-2 text-sm text-base-content/70">
            {{ $helperText }}
        </p>
    @endif

    @if($error)
        <p class="mt-2 text-sm font-medium text-error">
            {{ $error }}
        </p>
    @endif
</div>