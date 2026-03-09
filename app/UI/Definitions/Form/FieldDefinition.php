<?php

namespace App\UI\Definitions;

use App\UI\Types\FieldInput;
use App\UI\Types\FieldType;

final class FieldDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly FieldType $dataType,
        public readonly bool $required = false,
        public readonly bool $nullable = false,
        public readonly bool $multiple = false,
        public readonly mixed $default = null,
        public readonly array $meta = [],
        public readonly ?string $label = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'dataType' => $this->dataType->value,
            'label' => $this->label,
            'required' => $this->required,
            'nullable' => $this->nullable,
            'multiple' => $this->multiple,
            'default' => $this->default,
            'meta' => $this->meta,
        ];
    }
}