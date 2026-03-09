<?php

namespace App\UI\Builders;

use App\UI\Builders\FormFieldsetBuilder;

interface FormNodeCollectionBuilderInterface
{
    public function field(
        string $field,
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
        ?int $xl = null,
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

    public function fieldset(
        string $key,
        ?string $label = null,
        ?callable $callback = null,
        ?string $description = null,
        bool $collapsible = false,
        bool $collapsed = false,
        ?int $columns = null,
        array $meta = [],
    ): FormFieldsetBuilder;
}