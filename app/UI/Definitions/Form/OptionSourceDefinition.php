<?php

namespace App\UI\Definitions\Form;

final class OptionSourceDefinition
{
    public function __construct(
        public readonly string $source,
        public readonly string $valueField = 'id',
        public readonly string $labelField = 'label',
        public readonly array $filters = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'value_field' => $this->valueField,
            'label_field' => $this->labelField,
            'filters' => $this->filters,
            'meta' => $this->meta,
        ];
    }
}