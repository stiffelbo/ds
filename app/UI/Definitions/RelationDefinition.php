<?php

namespace App\UI\Definitions;

final class RelationDefinition
{
    public function __construct(
        public readonly string $field,
        public readonly string $target,
        public readonly string $labelField = 'name',
        public readonly string $valueField = 'id',
        public readonly bool $multiple = false,
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'target' => $this->target,
            'label_field' => $this->labelField,
            'value_field' => $this->valueField,
            'multiple' => $this->multiple,
            'meta' => $this->meta,
        ];
    }
}