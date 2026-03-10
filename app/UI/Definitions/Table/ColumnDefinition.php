<?php

namespace App\UI\Definitions\Table;

use App\UI\Types\TableCellType;

final class ColumnDefinition
{
    public function __construct(
        public readonly string $field,
        public readonly ?string $label = null,
        public readonly TableCellType $cellType = TableCellType::Text,
        public readonly bool $hidden = false,
        public readonly bool $editable = false,
        public readonly ?int $width = null,
        public readonly ?string $formatter = null,
        public readonly ?string $aggregation = null,
        public readonly ?array $options = null,
        public readonly ?string $fieldGroup = null,
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'label' => $this->label,
            'cellType' => $this->cellType?->value,
            'hidden' => $this->hidden,
            'editable' => $this->editable,
            'width' => $this->width,
            'formatter' => $this->formatter,
            'aggregation' => $this->aggregation,
            'options' => $this->options,
            'fieldGroup' => $this->fieldGroup,
            'meta' => $this->meta,
        ];
    }
}