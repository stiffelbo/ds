@php
    $headingTag = 'h' . min($level + 1, 6);

    // Nośność zależna od poziomu
    $wrapClass = match(true) {
        $level <= 1 => 'rounded-2xl border border-base-300 bg-base-100 shadow-sm',
        $level === 2 => 'rounded-xl border border-base-300 bg-base-100/60',
        default => 'rounded-lg border border-base-300/60 bg-base-100/30',
    };

    $headerPad = match(true) {
        $level <= 1 => 'px-5 py-4',
        $level === 2 => 'px-4 py-3',
        default => 'px-3 py-2.5',
    };

    $bodyPad = match(true) {
        $level <= 1 => 'px-5 pb-5',
        $level === 2 => 'px-4 pb-4',
        default => 'px-3 pb-3',
    };

    $titleClass = match(true) {
        $level <= 1 => 'text-xl',
        $level === 2 => 'text-lg',
        $level === 3 => 'text-base',
        default => 'text-sm',
    };

    // Akcent headera po lewej + delikatne podkreślenie
    $accentBarClass = match(true) {
        $level <= 1 => 'bg-primary',
        $level === 2 => 'bg-secondary',
        default => 'bg-base-content/20',
    };

    $underlineClass = match(true) {
        $level <= 1 => 'border-base-300',
        $level === 2 => 'border-base-300/80',
        default => 'border-base-300/60',
    };

    $hasBody = !empty($node['body']);
    $hasID = !empty($node['id']);
    $hasChildren = !empty($node['children']) && is_array($node['children']);

    if(!$hasID) {
        // Generuj ID z labelu jeśli nie ma, ale ma dzieci (jest ważny dla TOC)
        var_dump($node);
    }
@endphp
{{-- Body --}}
@if($hasID)
<div class="mt-5" id="{{$node['id']}}">
    {{-- Header (card header) --}}
    <div class="relative {{ $headerPad }} bg-base-100/40">
        <div class="absolute left-0 top-0 h-full w-1.5 {{ $accentBarClass }} rounded-l-2xl"></div>

        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <{{ $headingTag }} class="font-semibold {{ $titleClass }} leading-snug">
                {{ $node['label'] ?? '' }}
            </{{ $headingTag }}>

            {{-- Optional: mały hint/ID --}}
            @if(!empty($node['id']) && $level <= 2)
                <div class="mt-1 text-xs text-base-content/50 font-mono">
                    {{ $node['id'] }}
                </div>
            @endif
        </div>
    </div>
    <div class="mt-3 border-b {{ $underlineClass }}"></div>
</div>
@endif
{{-- Body --}}
@if($hasBody)
    <div class="{{ $bodyPad }}">
        @if(is_array($node['body']))
            @foreach($node['body'] as $paragraph)
                <p class="mb-2 text-base-content/80 leading-relaxed">
                    {{ $paragraph }}
                </p>
            @endforeach
        @else
            <p class="mb-2 text-base-content/80 leading-relaxed">
                {{ $node['body'] }}
            </p>
        @endif
    </div>
@endif

{{-- Children --}}
@if($hasChildren)
    <div class="{{ $bodyPad }} pt-0">

        @foreach($node['children'] as $child)
            @include('components.contract.node', [
                'node' => $child,
                'level' => $level + 1,
                'visited' => $visited ?? [],
                'maxDepth' => $maxDepth ?? 30,
            ])
        @endforeach
    </div>
@endif
