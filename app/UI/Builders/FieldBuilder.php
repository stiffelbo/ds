<?php

namespace App\UI\Builders;

use App\UI\Definitions\FieldDefinition;
use App\UI\Types\FieldType;
use InvalidArgumentException;

final class FieldBuilder
{
    protected ?FieldType $dataType = null;
    protected bool $required = false;
    protected bool $nullable = false;
    protected bool $multiple = false;
    protected mixed $default = null;
    protected array $meta = [];
    protected ?string $label = null;

    private function __construct(
        protected string $name,
    ) {
        if (trim($this->name) === '') {
            throw new InvalidArgumentException('Field name cannot be empty.');
        }
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function type(FieldType $dataType): self
    {
        $this->dataType = $dataType;

        return $this;
    }

    public function string(): self
    {
        return $this->type(FieldType::String);
    }

    public function number(): self
    {
        return $this->type(FieldType::Number);
    }

    public function bool(): self
    {
        return $this->type(FieldType::Bool);
    }

    public function date(): self
    {
        return $this->type(FieldType::Date);
    }

    public function dateTime(): self
    {
        return $this->type(FieldType::DateTime);
    }

    public function fk(): self
    {
        return $this->type(FieldType::Fk);
    }

    public function label(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        if ($required) {
            $this->nullable = false;
        }

        return $this;
    }

    public function nullable(bool $nullable = true): self
    {
        $this->nullable = $nullable;

        if ($nullable) {
            $this->required = false;
        }

        return $this;
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function default(mixed $default): self
    {
        $this->default = $default;

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

    public function build(): FieldDefinition
    {
        if (!$this->dataType instanceof FieldType) {
            throw new InvalidArgumentException(
                "Field [{$this->name}] must define a data type."
            );
        }

        return new FieldDefinition(
            name: $this->name,
            dataType: $this->dataType,
            required: $this->required,
            nullable: $this->nullable,
            multiple: $this->multiple,
            default: $this->default,
            meta: $this->meta,
            label: $this->label,
        );
    }
}