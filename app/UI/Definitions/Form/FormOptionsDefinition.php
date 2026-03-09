<?php

namespace App\UI\Definitions\Form;

final class FormOptionsDefinition
{
    /**
     * @param array<int, OptionItemDefinition> $items
     */
    public function __construct(
        public readonly array $items = [],
        public readonly ?OptionSourceDefinition $source = null,
        public readonly bool $multiple = false,
        public readonly bool $searchable = false,
        public readonly bool $clearable = false,
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'items' => array_map(
                fn (OptionItemDefinition $item) => $item->toArray(),
                $this->items
            ),
            'source' => $this->source?->toArray(),
            'multiple' => $this->multiple,
            'searchable' => $this->searchable,
            'clearable' => $this->clearable,
            'meta' => $this->meta,
        ];
    }
}