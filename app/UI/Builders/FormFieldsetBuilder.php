<?php

namespace App\UI\Builders;

use App\UI\Builders\FormNodeCollectionBuilderInterface;
use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Definitions\Form\FormCustomDefinition;
use App\UI\Definitions\Form\FormFieldDefinition;
use App\UI\Definitions\Form\FormFieldsetDefinition;
use App\UI\Definitions\Form\FormOptionsDefinition;
use App\UI\Definitions\Form\FormValidationDefinition;

class FormFieldsetBuilder implements FormNodeCollectionBuilderInterface
{
    /** @var array<int, FormNodeDefinition> */
    protected array $nodes = [];

    public function __construct(
        protected FormBuilder|FormFieldsetBuilder $parent,
        protected string $key,
        protected ?string $label = null,
        protected ?string $description = null,
        protected bool $collapsible = false,
        protected bool $collapsed = false,
        protected ?int $columns = null,
        protected array $meta = [],
    ) {}

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
    ): static {
        if ($options !== null && !$options instanceof FormOptionsDefinition) {
            throw new \InvalidArgumentException('Form field options must be instance of FormOptionsDefinition or null.');
        }

        if ($validation !== null && !$validation instanceof FormValidationDefinition) {
            throw new \InvalidArgumentException('Form field validation must be instance of FormValidationDefinition or null.');
        }

        $this->nodes[] = new FormFieldDefinition(
            field: $field,
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
        $this->nodes[] = new FormCustomDefinition(
            component: $component,
            key: $key,
            label: $label,
            props: $props,
            meta: $meta,
        );

        return $this;
    }

    public function fieldset(
        string $key,
        ?string $label = null,
        ?callable $callback = null,
        ?string $description = null,
        bool $collapsible = false,
        bool $collapsed = false,
        ?int $columns = null,
        array $meta = [],
    ): FormFieldsetBuilder {
        $builder = new self(
            parent: $this,
            key: $key,
            label: $label,
            description: $description,
            collapsible: $collapsible,
            collapsed: $collapsed,
            columns: $columns,
            meta: $meta,
        );

        if ($callback) {
            $callback($builder);
            return $builder->end();
        }

        return $builder;
    }

    public function end(): FormBuilder|FormFieldsetBuilder
    {
        $this->parent->pushNode(
            new FormFieldsetDefinition(
                key: $this->key,
                label: $this->label,
                description: $this->description,
                nodes: $this->nodes,
                collapsible: $this->collapsible,
                collapsed: $this->collapsed,
                columns: $this->columns,
                meta: $this->meta,
            )
        );

        return $this->parent;
    }

    public function pushNode(FormNodeDefinition $node): void
    {
        $this->nodes[] = $node;
    }
}