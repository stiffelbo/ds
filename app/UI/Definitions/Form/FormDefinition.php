<?php

namespace App\UI\Definitions\Form;

use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Types\FormSubmitMode;

final class FormDefinition
{
    /**
     * @param array<int, FormNodeDefinition> $nodes
     */
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly FormSubmitMode $submitMode = FormSubmitMode::Manual,
        public readonly bool $hasSubmit = true,
        public readonly ?string $submitLabel = 'Save',
        public readonly array $nodes = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'submit_mode' => $this->submitMode->value,
            'has_submit' => $this->hasSubmit,
            'submit_label' => $this->submitLabel,
            'nodes' => array_map(
                fn (FormNodeDefinition $node) => $node->toArray(),
                $this->nodes
            ),
            'meta' => $this->meta,
        ];
    }
}