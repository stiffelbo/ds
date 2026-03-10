<?php

namespace App\UI\Builders;

use App\UI\Definitions\Table\ColumnDefinition;
use App\UI\Definitions\Table\TableDefinition;
use App\UI\Types\TableCellType;
use InvalidArgumentException;

final class TableBuilder
{
    /** @var array<int, ColumnDefinition> */
    protected array $columns = [];

    protected ?array $defaultSort = null;
    protected array $defaultFilters = [];
    protected array $defaultGrouping = [];
    protected array $meta = [];

    private function __construct(
        protected string $key,
        protected string $label,
    ) {
        if (trim($this->key) === '') {
            throw new InvalidArgumentException('Table key cannot be empty.');
        }

        if (trim($this->label) === '') {
            throw new InvalidArgumentException('Table label cannot be empty.');
        }
    }

    public static function make(string $key, string $label): self
    {
        return new self($key, $label);
    }

    public function column(
        string $field,
        ?string $label = null,
        TableCellType $cellType = TableCellType::Text,
        bool $hidden = false,
        bool $editable = false,
        ?int $width = null,
        ?string $formatter = null,
        ?string $aggregation = null,
        ?array $options = null,
        ?string $fieldGroup = null,
        array $meta = [],
    ): self {
        $this->columns[] = new ColumnDefinition(
            field: $field,
            label: $label,
            cellType: $cellType,
            hidden: $hidden,
            editable: $editable,
            width: $width,
            formatter: $formatter,
            aggregation: $aggregation,
            options: $options,
            fieldGroup: $fieldGroup,
            meta: $meta,
        );

        return $this;
    }

    public function addColumn(ColumnDefinition $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    public function defaultSort(string $field, string $dir = 'asc'): self
    {
        $dir = strtolower($dir);

        if (!in_array($dir, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Table defaultSort direction must be asc or desc.');
        }

        $this->defaultSort = [
            'field' => $field,
            'dir' => $dir,
        ];

        return $this;
    }

    public function defaultFilters(array $defaultFilters): self
    {
        $this->defaultFilters = $defaultFilters;

        return $this;
    }

    public function defaultGrouping(array $defaultGrouping): self
    {
        $this->defaultGrouping = $defaultGrouping;

        return $this;
    }

    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function addMeta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;

        return $this;
    }

    public function build(): TableDefinition
    {
        return new TableDefinition(
            key: $this->key,
            label: $this->label,
            columns: $this->columns,
            defaultSort: $this->defaultSort,
            defaultFilters: $this->defaultFilters,
            defaultGrouping: $this->defaultGrouping,
            meta: $this->meta,
        );
    }
}