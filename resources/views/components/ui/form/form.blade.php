@props([
    'runtime',
    'action' => null,
    'method' => 'POST',
])

@php
    $form = $runtime['form'] ?? [];
    $nodes = $form['nodes'] ?? [];
    $hasSubmit = (bool) ($form['has_submit'] ?? true);
    $submitLabel = $form['submit_label'] ?? 'Save';
    $formMethod = strtoupper($method);
@endphp

<form
    action="{{ $action }}"
    method="{{ in_array($formMethod, ['GET', 'POST'], true) ? $formMethod : 'POST' }}"
    class="space-y-8"
>
    @csrf

    @if(!in_array($formMethod, ['GET', 'POST'], true))
        @method($formMethod)
    @endif

    @if(!empty($runtime['title']) || !empty($runtime['description']))
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-6">
                @if(!empty($runtime['title']))
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ $runtime['title'] }}
                    </h1>
                @endif

                @if(!empty($runtime['description']))
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-base-content/70">
                        {{ $runtime['description'] }}
                    </p>
                @endif

                @if(!empty($runtime['help']) && is_array($runtime['help']))
                    <div class="mt-4 space-y-2">
                        @foreach($runtime['help'] as $hint)
                            <div class="alert alert-soft">
                                <span class="text-sm">{{ $hint }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="space-y-6">
        @foreach($nodes as $node)
            <x-ui.form.node :node="$node" />
        @endforeach
    </div>

    @if($hasSubmit)
        <div class="flex items-center justify-end gap-3 border-t border-base-300 pt-6">
            <button
                type="submit"
                class="btn btn-neutral"
            >
                {{ $submitLabel }}
            </button>
        </div>
    @endif
</form>