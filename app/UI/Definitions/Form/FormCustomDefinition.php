<?php

namespace App\UI\Definitions\Form;

use App\UI\Definitions\Form\FormNodeDefinition;

final class FormCustomDefinition implements FormNodeDefinition
{
    public function __construct(
        public readonly string $component,
        public readonly ?string $key = null,
        public readonly ?string $label = null,
        public readonly array $props = [],
        public readonly array $meta = [],
    ) {}

    public function nodeType(): string
    {
        return 'custom';
    }

    public function toArray(): array
    {
        return [
            'node_type' => $this->nodeType(),
            'component' => $this->component,
            'key' => $this->key,
            'label' => $this->label,
            'props' => $this->props,
            'meta' => $this->meta,
        ];
    }
}