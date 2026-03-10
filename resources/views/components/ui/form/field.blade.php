@props([
    'node',
])

@php
    if (($node['hidden'] ?? false) === true) {
        return;
    }

    $input = $node['input'] ?? 'text';
@endphp

@if($input === 'select')
    <x-ui.input.select :node="$node" />
@elseif($input === 'textarea')
    <x-ui.input.textarea :node="$node" />
@elseif($input === 'checkbox' || $input === 'bool')
    <x-ui.input.checkbox :node="$node" />
@elseif($input === 'radio')
    <x-ui.input.radio :node="$node" />
@else
    <x-ui.input.text :node="$node" />
@endif