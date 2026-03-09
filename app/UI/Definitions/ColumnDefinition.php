<?php

namespace App\UI\Definitions;

final class ColumnDefinition
{
    public function __construct(
        public readonly string $field,
        public readonly ?string $label = null,
        public readonly ?int $width = null,
        public readonly bool $editable = false,
        public readonly bool $sortable = false,
        public readonly bool $hidden = false,
        public readonly ?string $formatter = null,
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'label' => $this->label,
            'width' => $this->width,
            'editable' => $this->editable,
            'sortable' => $this->sortable,
            'hidden' => $this->hidden,
            'formatter' => $this->formatter,
            'meta' => $this->meta,
        ];
    }
}