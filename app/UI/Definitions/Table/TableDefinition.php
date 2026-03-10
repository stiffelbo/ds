<?php

namespace App\UI\Definitions\Table;

final class TableDefinition
{
    /**
     * @param array<int, ColumnDefinition> $columns
     */
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly array $columns = [],
        public readonly ?array $defaultSort = null,
        public readonly array $defaultFilters = [],
        public readonly array $defaultGrouping = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'columns' => array_map(
                fn (ColumnDefinition $column) => $column->toArray(),
                $this->columns
            ),
            'defaultSort' => $this->defaultSort,
            'defaultFilters' => array_values($this->defaultFilters),
            'defaultGrouping' => array_values($this->defaultGrouping),
            'meta' => $this->meta,
        ];
    }
}