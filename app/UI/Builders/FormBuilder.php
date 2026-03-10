<?php

namespace App\UI\Builders;

use App\UI\Builders\FormNodeCollectionBuilderInterface;
use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Definitions\Form\FormCustomDefinition;
use App\UI\Definitions\Form\FormDefinition;
use App\UI\Definitions\Form\FormFieldDefinition;
use App\UI\Definitions\Form\FormOptionsDefinition;
use App\UI\Definitions\Form\FormValidationDefinition;
use App\UI\Types\FieldInput;
use App\UI\Types\FieldInputSize;
use App\UI\Types\FieldInputVariant;
use App\UI\Types\FormSubmitMode;

class FormBuilder implements FormNodeCollectionBuilderInterface
{
    /** @var array<int, FormNodeDefinition> */
    protected array $nodes = [];

    public function __construct(
        protected string $key,
        protected string $label,
        protected FormSubmitMode $submitMode = FormSubmitMode::Manual,
        protected bool $hasSubmit = true,
        protected ?string $submitLabel = 'Save',
        protected array $meta = [],
    ) {}

    public static function make(
        string $key,
        string $label,
        FormSubmitMode $submitMode = FormSubmitMode::Manual,
        bool $hasSubmit = true,
        ?string $submitLabel = 'Save',
        array $meta = [],
    ): self {
        return new self(
            key: $key,
            label: $label,
            submitMode: $submitMode,
            hasSubmit: $hasSubmit,
            submitLabel: $submitLabel,
            meta: $meta,
        );
    }

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
        ?int $xl = null,
        FieldInputSize $size = FieldInputSize::Small,
        FieldInputVariant $variant = FieldInputVariant::Outlined,
        ?int $rows = null,
        mixed $default = null,
        ?string $component = null,
        mixed $options = null,
        mixed $validation = null,
        array $meta = [],
    ): static {
        if ($options !== null && !$options instanceof FormOptionsDefinition) {
            throw new \InvalidArgumentException('Form field options must be instance of FormOptionsDefinition or null.');
        }

        if ($validation !== null && !$validation instanceof FormValidationDefinition) {
            throw new \InvalidArgumentException('Form field validation must be instance of FormValidationDefinition or null.');
        }

        $this->nodes[] = new FormFieldDefinition(
            field: $field,
            input: $input,
            label: $label,
            placeholder: $placeholder,
            helperText: $helperText,
            required: $required,
            hidden: $hidden,
            readonly: $readonly,
            disabled: $disabled,
            multiple: $multiple,
            xs: $xs,
            md: $md,
            xl: $xl,
            size: $size,
            variant: $variant,
            rows: $rows,
            default: $default,
            component: $component,
            options: $options,
            validation: $validation,
            meta: $meta,
        );

        return $this;
    }

    public function custom(
        string $component,
        ?string $key = null,
        ?string $label = null,
        array $props = [],
        array $meta = [],
    ): static {
        $this->nodes[] = new FormCustomDefinition(
            component: $component,
            key: $key,
            label: $label,
            props: $props,
            meta: $meta,
        );

        return $this;
    }

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
): FormGroupBuilder {
    return new FormGroupBuilder(
        parent: $this,
        key: $key,
        label: $label,
        description: $description,
        xs: $xs,
        md: $md,
        xl: $xl,
        collapsible: $collapsible,
        collapsed: $collapsed,
        variant: $variant,
        meta: $meta,
    );
}

    public function pushNode(FormNodeDefinition $node): void
    {
        $this->nodes[] = $node;
    }

    public function submitMode(FormSubmitMode $submitMode): static
    {
        $this->submitMode = $submitMode;
        return $this;
    }

    public function hasSubmit(bool $hasSubmit = true): static
    {
        $this->hasSubmit = $hasSubmit;
        return $this;
    }

    public function submitLabel(?string $submitLabel): static
    {
        $this->submitLabel = $submitLabel;
        return $this;
    }

    public function meta(array $meta): static
    {
        $this->meta = $meta;
        return $this;
    }

    public function build(): FormDefinition
    {
        return new FormDefinition(
            key: $this->key,
            label: $this->label,
            submitMode: $this->submitMode,
            hasSubmit: $this->hasSubmit,
            submitLabel: $this->submitLabel,
            nodes: $this->nodes,
            meta: $this->meta,
        );
    }

    public function toArray(): array
    {
        return $this->build()->toArray();
    }
}