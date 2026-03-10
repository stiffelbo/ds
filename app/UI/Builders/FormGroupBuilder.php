<?php

namespace App\UI\Builders;

use App\UI\Definitions\Form\FormCustomDefinition;
use App\UI\Definitions\Form\FormFieldDefinition;
use App\UI\Definitions\Form\FormGroupDefinition;
use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Definitions\Form\FormOptionsDefinition;
use App\UI\Definitions\Form\FormValidationDefinition;
use App\UI\Types\FieldInput;
use InvalidArgumentException;

class FormGroupBuilder implements FormNodeCollectionBuilderInterface
{
    /** @var array<int, FormNodeDefinition> */
    protected array $children = [];

    public function __construct(
        protected FormBuilder|FormGroupBuilder $parent,
        protected string $key,
        protected ?string $label = null,
        protected ?string $description = null,
        protected int $xs = 12,
        protected int $md = 12,
        protected ?int $xl = null,
        protected bool $collapsible = false,
        protected bool $collapsed = false,
        protected ?string $variant = null,
        protected array $meta = [],
    ) {}

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
        ?int $rows = null,
        mixed $default = null,
        ?string $component = null,
        mixed $options = null,
        mixed $validation = null,
        array $meta = [],
    ): static {
        if ($options !== null && !$options instanceof FormOptionsDefinition) {
            throw new InvalidArgumentException(
                'Form field options must be instance of FormOptionsDefinition or null.'
            );
        }

        if ($validation !== null && !$validation instanceof FormValidationDefinition) {
            throw new InvalidArgumentException(
                'Form field validation must be instance of FormValidationDefinition or null.'
            );
        }

        $this->children[] = new FormFieldDefinition(
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
        $this->children[] = new FormCustomDefinition(
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
        return new self(
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

    public function groupBlock(
        string $key,
        ?string $label,
        callable $callback,
        ?string $description = null,
        int $xs = 12,
        int $md = 12,
        ?int $xl = null,
        bool $collapsible = false,
        bool $collapsed = false,
        ?string $variant = null,
        array $meta = [],
    ): static {
        $builder = $this->group(
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

        $callback($builder);
        $builder->end();

        return $this;
    }

    public function pushNode(FormNodeDefinition $node): void
    {
        $this->children[] = $node;
    }

    public function end(): FormBuilder|FormGroupBuilder
    {
        $this->parent->pushNode(
            new FormGroupDefinition(
                key: $this->key,
                label: $this->label,
                description: $this->description,
                xs: $this->xs,
                md: $this->md,
                xl: $this->xl,
                children: $this->children,
                collapsible: $this->collapsible,
                collapsed: $this->collapsed,
                variant: $this->variant,
                meta: $this->meta,
            )
        );

        return $this->parent;
    }
}