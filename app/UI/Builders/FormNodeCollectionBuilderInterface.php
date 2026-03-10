<?php

namespace App\UI\Builders;

use App\UI\Builders\FormGroupBuilder;
use App\UI\Types\FieldInput;

interface FormNodeCollectionBuilderInterface
{
    public function field(
        string $field,
        FieldInput $input = FieldInput::Text,
        ?string $label = null,
        ?string $placeholder = null,
        ?string $helperText = null,
        ?bool $required = null,
        bool $hidden = false,
        bool $readonly = false,
        bool $disabled = false,
        bool $multiple = false,
        int $xs = 12,
        int $md = 6,
        ?int $xl = 3,
        ?int $rows = null,
        mixed $default = null,
        ?string $component = null,
        mixed $options = null,
        mixed $validation = null,
        array $meta = [],
    ): static;

    public function custom(
        string $component,
        ?string $key = null,
        ?string $label = null,
        array $props = [],
        array $meta = [],
    ): static;

    public function group(
        string $key,
        ?string $label = null,
        ?string $description = null,
        int $xs = 12,
        int $md = 12,
        ?int $xl = null,
        bool $collapsible = false,
        bool $collapsed = false,
        ?string $variant = null,
        array $meta = [],
    ): FormGroupBuilder;
}