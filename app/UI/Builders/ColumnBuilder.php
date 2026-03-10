<?php

namespace App\UI\Builders;

use App\UI\Definitions\Table\ColumnDefinition;
use App\UI\Types\TableCellType;
use InvalidArgumentException;

final class ColumnBuilder
{
    protected ?string $label = null;
    protected TableCellType $cellType = TableCellType::Text;
    protected bool $hidden = false;
    protected bool $editable = false;
    protected bool $sortable = true;
    protected bool $filterable = false;
    protected bool $groupable = false;
    protected ?int $width = null;
    protected ?string $formatter = null;
    protected ?string $aggregation = null;
    protected ?array $options = null;
    protected ?string $fieldGroup = null;
    protected array $meta = [];

    private function __construct(
        protected string $field,
    ) {
        if (trim($this->field) === '') {
            throw new InvalidArgumentException('Column field cannot be empty.');
        }
    }

    public static function make(string $field): self
    {
        return new self($field);
    }

    public function label(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function cellType(?TableCellType $cellType): self
    {
        $this->cellType = $cellType;
        return $this;
    }

    public function hidden(bool $hidden = true): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function editable(bool $editable = true): self
    {
        $this->editable = $editable;

        return $this;
    }

    public function width(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function formatter(?string $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function aggregation(?string $aggregation): self
    {
        $this->aggregation = $aggregation;

        return $this;
    }

    public function options(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function fieldGroup(?string $fieldGroup): self
    {
        $this->fieldGroup = $fieldGroup;

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

    public function build(): ColumnDefinition
    {
        return new ColumnDefinition(
            field: $this->field,
            label: $this->label,
            hidden: $this->hidden,
            editable: $this->editable,
            width: $this->width,
            formatter: $this->formatter,
            aggregation: $this->aggregation,
            options: $this->options,
            fieldGroup: $this->fieldGroup,
            meta: $this->meta,
        );
    }
}