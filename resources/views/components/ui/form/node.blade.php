@props([
    'node',
])

@php
    $nodeType = $node['node_type'] ?? null;
@endphp

@if($nodeType === 'group')
    <x-ui.form.group :node="$node" />
@elseif($nodeType === 'field')
    <x-ui.form.field :node="$node" />
@elseif($nodeType === 'custom')
    <x-ui.form.custom :node="$node" />
@endif