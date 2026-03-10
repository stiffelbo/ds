<?php

namespace App\UI\Definitions\Form;

use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Types\FieldInputSize;

final class FormGroupDefinition implements FormNodeDefinition
{
    /**
     * @param array<int, FormNodeDefinition> $children
     */
    public function __construct(
        public readonly string $key,
        public readonly ?string $label = null,
        public readonly ?string $description = null,
        public readonly int $xs = 12,
        public readonly int $md = 12,
        public readonly ?int $xl = null,
        public readonly array $children = [],
        public readonly bool $collapsible = false,
        public readonly bool $collapsed = false,
        public readonly ?string $variant = null,
        public readonly array $meta = [],
    ) {}

    public function nodeType(): string
    {
        return 'group';
    }

    public function toArray(): array
    {
        return [
            'node_type' => 'group',
            'key' => $this->key,
            'label' => $this->label,
            'description' => $this->description,
            'xs' => $this->xs,
            'md' => $this->md,
            'xl' => $this->xl,
            'children' => array_map(
                fn (FormNodeDefinition $node) => $node->toArray(),
                $this->children
            ),
            'collapsible' => $this->collapsible,
            'collapsed' => $this->collapsed,
            'variant' => $this->variant,
            'meta' => $this->meta,
        ];
    }
}