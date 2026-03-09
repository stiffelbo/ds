<?php

namespace App\UI;

use App\UI\Definitions\FieldDefinition;
use App\UI\Definitions\RelationDefinition;
use App\UI\Definitions\Form\FormNodeDefinition;
use App\UI\Definitions\Form\FormCustomDefinition;
use App\UI\Definitions\Form\FormDefinition;
use App\UI\Definitions\Form\FormFieldDefinition;
use App\UI\Definitions\Form\FormFieldsetDefinition;
use InvalidArgumentException;

abstract class BaseUi
{
    abstract protected function entity(): string;

    /**
     * Global human title for the entity UI definition.
     */
    protected function title(): ?string
    {
        return null;
    }

    /**
     * General description for dashboard/help/docs usage.
     */
    protected function description(): ?string
    {
        return null;
    }

    /**
     * Additional help blocks / hints / notes.
     *
     * @return array<int, string>
     */
    protected function help(): array
    {
        return [];
    }

    /**
     * Root field registry.
     *
     * @return array<string, FieldDefinition>
     */
    abstract protected function fields(): array;

    /**
     * Field relations / lookup definitions.
     *
     * @return array<string, RelationDefinition>
     */
    protected function relations(): array
    {
        return [];
    }

    /**
     * All forms for the entity, keyed by form key.
     *
     * Example:
     * [
     *   'add' => FormDefinition(...),
     *   'edit' => FormDefinition(...),
     * ]
     *
     * @return array<string, FormDefinition>
     */
    protected function forms(): array
    {
        return [];
    }

    /**
     * Future renderer hooks.
     * For now they are pass-through extension points.
     */
    protected function tables(): array
    {
        return [];
    }

    protected function cards(): array
    {
        return [];
    }

    protected function calendars(): array
    {
        return [];
    }

    protected function widgets(): array
    {
        return [];
    }

    public function toArray(): array
    {
        $fields = $this->fields();
        $relations = $this->relations();
        $forms = $this->forms();

        $this->assertEntity();
        $this->assertFields($fields);
        $this->assertRelations($relations, $fields);
        $this->assertForms($forms, $fields);

        return [
            'entity' => $this->entity(),
            'title' => $this->title(),
            'description' => $this->description(),
            'help' => array_values($this->help()),

            'fields' => array_map(
                fn (FieldDefinition $field) => $field->toArray(),
                $fields
            ),

            'relations' => array_map(
                fn (RelationDefinition $relation) => $relation->toArray(),
                $relations
            ),

            'forms' => array_map(
                fn (FormDefinition $form) => $form->toArray(),
                $forms
            ),

            // Future-facing extension points
            'tables' => $this->serializeExtensionDefinitions($this->tables()),
            'cards' => $this->serializeExtensionDefinitions($this->cards()),
            'calendars' => $this->serializeExtensionDefinitions($this->calendars()),
            'widgets' => $this->serializeExtensionDefinitions($this->widgets()),
        ];
    }

    protected function assertEntity(): void
    {
        if (trim($this->entity()) === '') {
            throw new InvalidArgumentException('UI entity key cannot be empty.');
        }
    }

    /**
     * @param array<string, FieldDefinition> $fields
     */
    protected function assertFields(array $fields): void
    {
        foreach ($fields as $name => $field) {
            if (!$field instanceof FieldDefinition) {
                throw new InvalidArgumentException(
                    "Field [{$name}] must be instance of FieldDefinition."
                );
            }

            if ($field->name !== $name) {
                throw new InvalidArgumentException(
                    "Field key [{$name}] must match FieldDefinition name [{$field->name}]."
                );
            }
        }
    }

    /**
     * @param array<string, RelationDefinition> $relations
     * @param array<string, FieldDefinition> $fields
     */
    protected function assertRelations(array $relations, array $fields): void
    {
        foreach ($relations as $fieldName => $relation) {
            if (!$relation instanceof RelationDefinition) {
                throw new InvalidArgumentException(
                    "Relation [{$fieldName}] must be instance of RelationDefinition."
                );
            }

            if (!isset($fields[$fieldName])) {
                throw new InvalidArgumentException(
                    "Relation [{$fieldName}] references undefined field."
                );
            }

            if ($relation->field !== $fieldName) {
                throw new InvalidArgumentException(
                    "Relation key [{$fieldName}] must match RelationDefinition field [{$relation->field}]."
                );
            }
        }
    }

    /**
     * @param array<string, FormDefinition> $forms
     * @param array<string, FieldDefinition> $fields
     */
    protected function assertForms(array $forms, array $fields): void
    {
        foreach ($forms as $key => $form) {
            if (!$form instanceof FormDefinition) {
                throw new InvalidArgumentException(
                    "Form [{$key}] must be instance of FormDefinition."
                );
            }

            if ($form->key !== $key) {
                throw new InvalidArgumentException(
                    "Form key [{$key}] must match FormDefinition key [{$form->key}]."
                );
            }

            $this->assertFormNodes($form->nodes, $fields, $key);
        }
    }

    /**
     * @param array<int, FormNodeDefinition> $nodes
     * @param array<string, FieldDefinition> $fields
     */
    protected function assertFormNodes(array $nodes, array $fields, string $formKey): void
    {
        foreach ($nodes as $index => $node) {
            if (!$node instanceof FormNodeDefinition) {
                throw new InvalidArgumentException(
                    "Form [{$formKey}] contains invalid node at index [{$index}]."
                );
            }

            if ($node instanceof FormFieldDefinition) {
                if (!isset($fields[$node->field])) {
                    throw new InvalidArgumentException(
                        "Form [{$formKey}] references undefined field [{$node->field}]."
                    );
                }

                continue;
            }

            if ($node instanceof FormFieldsetDefinition) {
                $this->assertFormNodes($node->nodes, $fields, $formKey);
                continue;
            }

            if ($node instanceof FormCustomDefinition) {
                continue;
            }

            throw new InvalidArgumentException(
                "Form [{$formKey}] contains unsupported node type [".get_class($node)."]."
            );
        }
    }

    protected function serializeExtensionDefinitions(array $definitions): array
    {
        $serialized = [];

        foreach ($definitions as $key => $definition) {
            if (is_object($definition) && method_exists($definition, 'toArray')) {
                $serialized[$key] = $definition->toArray();
                continue;
            }

            if (is_array($definition)) {
                $serialized[$key] = $definition;
                continue;
            }

            throw new InvalidArgumentException(
                "Extension definition [{$key}] must be array or object with toArray()."
            );
        }

        return $serialized;
    }
}