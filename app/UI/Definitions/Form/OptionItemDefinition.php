<?php

namespace App\UI\Definitions\Form;

final class OptionItemDefinition
{
    public function __construct(
        public readonly string|int $value,
        public readonly string $label,
        public readonly bool $disabled = false,
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
            'disabled' => $this->disabled,
            'meta' => $this->meta,
        ];
    }
}