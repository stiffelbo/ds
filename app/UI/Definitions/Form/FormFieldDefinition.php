<?php

namespace App\UI\Definitions\Form;

use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Types\FieldInput;

final class FormFieldDefinition implements FormNodeDefinition
{
    public function __construct(
        public readonly string $field,
        public readonly FieldInput $input = FieldInput::Text,
        public readonly ?string $label = null,
        public readonly ?string $placeholder = null,
        public readonly ?string $helperText = null,
        public readonly ?bool $required = null,
        public readonly bool $hidden = false,
        public readonly bool $readonly = false,
        public readonly bool $disabled = false,
        public readonly bool $multiple = false,
        public readonly int $xs = 12,
        public readonly int $md = 6,
        public readonly ?int $xl = 3,
        public readonly ?int $rows = null,
        public readonly mixed $default = null,
        public readonly ?string $component = null,
        public readonly ?FormOptionsDefinition $options = null,
        public readonly ?FormValidationDefinition $validation = null,
        public readonly array $meta = [],
    ) {}

    public function nodeType(): string
    {
        return 'field';
    }

    public function toArray(): array
    {
        return [
            'node_type' => $this->nodeType(),
            'field' => $this->field,
            'input' => $this->input->value,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'helper_text' => $this->helperText,
            'required' => $this->required,
            'hidden' => $this->hidden,
            'readonly' => $this->readonly,
            'disabled' => $this->disabled,
            'multiple' => $this->multiple,
            'xs' => $this->xs,
            'md' => $this->md,
            'xl' => $this->xl,
            'rows' => $this->rows,
            'default' => $this->default,
            'component' => $this->component,
            'options' => $this->options?->toArray(),
            'validation' => $this->validation?->toArray(),
            'meta' => $this->meta,
        ];
    }
}