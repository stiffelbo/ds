<?php

namespace App\UI\Definitions;

final class TableDefinition
{
    /**
     * @param array<int, ColumnDefinition> $columns
     */
    public function __construct(
        public readonly string $label,
        public readonly array $columns = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'columns' => array_map(
                fn (ColumnDefinition $column) => $column->toArray(),
                $this->columns
            ),
            'meta' => $this->meta,
        ];
    }
}