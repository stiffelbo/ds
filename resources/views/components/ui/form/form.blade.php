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
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @if(!empty($runtime['title']))
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                    {{ $runtime['title'] }}
                </h1>
            @endif

            @if(!empty($runtime['description']))
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                    {{ $runtime['description'] }}
                </p>
            @endif

            @if(!empty($runtime['help']) && is_array($runtime['help']))
                <div class="mt-4 space-y-2">
                    @foreach($runtime['help'] as $hint)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-200">
                            {{ $hint }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="space-y-6">
        @foreach($nodes as $node)
            <x-ui.form.node :node="$node" />
        @endforeach
    </div>

    @if($hasSubmit)
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2"
            >
                {{ $submitLabel }}
            </button>
        </div>
    @endif
</form>