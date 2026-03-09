<?php

namespace App\UI\Definitions\Form;

use App\UI\Definitions\Form\FormNodeDefinition;

final class FormFieldsetDefinition implements FormNodeDefinition
{
    /**
     * @param array<int, FormNodeDefinition> $nodes
     */
    public function __construct(
        public readonly string $key,
        public readonly ?string $label = null,
        public readonly ?string $description = null,
        public readonly array $nodes = [],
        public readonly bool $collapsible = false,
        public readonly bool $collapsed = false,
        public readonly ?int $columns = null,
        public readonly array $meta = [],
    ) {}

    public function nodeType(): string
    {
        return 'fieldset';
    }

    public function toArray(): array
    {
        return [
            'node_type' => $this->nodeType(),
            'key' => $this->key,
            'label' => $this->label,
            'description' => $this->description,
            'collapsible' => $this->collapsible,
            'collapsed' => $this->collapsed,
            'columns' => $this->columns,
            'nodes' => array_map(
                fn (FormNodeDefinition $node) => $node->toArray(),
                $this->nodes
            ),
            'meta' => $this->meta,
        ];
    }
}