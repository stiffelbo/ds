<?php

namespace App\UI\Runtime;

use App\UI\Types\FieldInputSize;
use App\UI\Types\FieldInputVariant;
use InvalidArgumentException;

class FormRuntimeResolver
{
    public function resolve(
        array $ui,
        string $formKey,
        array|object|null $record = null,
        array $values = [],
        array $errors = [],
        array $context = [],
    ): array {
        $entity = (string) ($ui['entity'] ?? '');
        $fields = is_array($ui['fields'] ?? null) ? $ui['fields'] : [];
        $forms = is_array($ui['forms'] ?? null) ? $ui['forms'] : [];

        if ($entity === '') {
            throw new InvalidArgumentException('UI contract must contain [entity].');
        }

        if (!isset($forms[$formKey]) || !is_array($forms[$formKey])) {
            throw new InvalidArgumentException("Form [{$formKey}] is not defined in UI contract.");
        }

        $form = $forms[$formKey];
        $recordData = $this->normalizeRecord($record);

        return [
            'entity' => $entity,
            'title' => $ui['title'] ?? null,
            'description' => $ui['description'] ?? null,
            'help' => array_values(is_array($ui['help'] ?? null) ? $ui['help'] : []),

            'form' => [
                'key' => $form['key'] ?? $formKey,
                'label' => $form['label'] ?? $formKey,
                'submit_mode' => $form['submit_mode'] ?? 'manual',
                'has_submit' => (bool) ($form['has_submit'] ?? true),
                'submit_label' => $form['submit_label'] ?? 'Save',
                'meta' => is_array($form['meta'] ?? null) ? $form['meta'] : [],
                'nodes' => $this->resolveNodes(
                    nodes: is_array($form['nodes'] ?? null) ? $form['nodes'] : [],
                    fields: $fields,
                    recordData: $recordData,
                    values: $values,
                    errors: $errors,
                    context: $context,
                ),
            ],
        ];
    }

    protected function resolveNodes(
        array $nodes,
        array $fields,
        array $recordData,
        array $values,
        array $errors,
        array $context = [],
    ): array {
        $resolved = [];

        foreach ($nodes as $node) {
            if (!is_array($node)) {
                continue;
            }

            $nodeType = (string) ($node['node_type'] ?? '');

            if ($nodeType === 'field') {
                $resolved[] = $this->resolveFieldNode(
                    node: $node,
                    fields: $fields,
                    recordData: $recordData,
                    values: $values,
                    errors: $errors,
                    context: $context,
                );
                continue;
            }

            if ($nodeType === 'group') {
                $resolved[] = $this->resolveGroupNode(
                    node: $node,
                    fields: $fields,
                    recordData: $recordData,
                    values: $values,
                    errors: $errors,
                    context: $context,
                );
                continue;
            }

            if ($nodeType === 'custom') {
                $resolved[] = $this->resolveCustomNode($node);
                continue;
            }

            throw new InvalidArgumentException("Unsupported form node_type [{$nodeType}].");
        }

        return $resolved;
    }

    protected function resolveFieldNode(
        array $node,
        array $fields,
        array $recordData,
        array $values,
        array $errors,
        array $context = [],
    ): array {
        $fieldName = (string) ($node['field'] ?? '');

        if ($fieldName === '') {
            throw new InvalidArgumentException('Form field node must contain [field].');
        }

        if (!isset($fields[$fieldName]) || !is_array($fields[$fieldName])) {
            throw new InvalidArgumentException("Field [{$fieldName}] is not defined in root fields registry.");
        }

        $field = $fields[$fieldName];

        $value = $this->resolveValue(
            fieldName: $fieldName,
            node: $node,
            field: $field,
            recordData: $recordData,
            values: $values,
        );

        $error = $this->resolveError($fieldName, $errors);

        return [
            'node_type' => 'field',

            'field' => $fieldName,
            'data_type' => $field['dataType'] ?? null,
            'input' => $node['input'] ?? null,

            'label' => $node['label'] ?? $field['label'] ?? $this->humanize($fieldName),
            'placeholder' => $node['placeholder'] ?? null,
            'helper_text' => $node['helper_text'] ?? null,

            'required' => array_key_exists('required', $node) && $node['required'] !== null
                ? (bool) $node['required']
                : (bool) ($field['required'] ?? false),

            'nullable' => (bool) ($field['nullable'] ?? false),
            'multiple' => array_key_exists('multiple', $node)
                ? (bool) $node['multiple']
                : (bool) ($field['multiple'] ?? false),

            'hidden' => (bool) ($node['hidden'] ?? false),
            'readonly' => (bool) ($node['readonly'] ?? false),
            'disabled' => (bool) ($node['disabled'] ?? false),

            'xs' => (int) ($node['xs'] ?? 12),
            'md' => (int) ($node['md'] ?? 6),
            'xl' => isset($node['xl']) ? (int) $node['xl'] : null,
            'rows' => isset($node['rows']) ? (int) $node['rows'] : null,

            'default' => $node['default'] ?? $field['default'] ?? null,
            'value' => $value,
            'error' => $error,
            'has_error' => $error !== null,

            'variant' => $node['variant'] ?? FieldInputVariant::Outlined->value,
            'size' => $node['size'] ?? FieldInputSize::Small->value,
            'component' => $node['component'] ?? null,
            'options' => $node['options'] ?? null,
            'validation' => $node['validation'] ?? null,

            'meta' => [
                'field' => is_array($field['meta'] ?? null) ? $field['meta'] : [],
                'node' => is_array($node['meta'] ?? null) ? $node['meta'] : [],
                'context' => $context,
            ],
        ];
    }

    protected function resolveGroupNode(
        array $node,
        array $fields,
        array $recordData,
        array $values,
        array $errors,
        array $context = [],
    ): array {
        return [
            'node_type' => 'group',
            'key' => $node['key'] ?? null,
            'label' => $node['label'] ?? null,
            'description' => $node['description'] ?? null,

            'xs' => (int) ($node['xs'] ?? 12),
            'md' => (int) ($node['md'] ?? 12),
            'xl' => isset($node['xl']) ? (int) $node['xl'] : null,

            'collapsible' => (bool) ($node['collapsible'] ?? false),
            'collapsed' => (bool) ($node['collapsed'] ?? false),
            'variant' => $node['variant'] ?? FieldInputVariant::Outlined->value,
            'size' => $node['size'] ?? FieldInputSize::Small->value,

            'children' => $this->resolveNodes(
                nodes: is_array($node['children'] ?? null) ? $node['children'] : [],
                fields: $fields,
                recordData: $recordData,
                values: $values,
                errors: $errors,
                context: $context,
            ),

            'meta' => is_array($node['meta'] ?? null) ? $node['meta'] : [],
        ];
    }

    protected function resolveCustomNode(array $node): array
    {
        return [
            'node_type' => 'custom',
            'component' => $node['component'] ?? null,
            'key' => $node['key'] ?? null,
            'label' => $node['label'] ?? null,
            'props' => is_array($node['props'] ?? null) ? $node['props'] : [],
            'meta' => is_array($node['meta'] ?? null) ? $node['meta'] : [],
        ];
    }

    protected function resolveValue(
        string $fieldName,
        array $node,
        array $field,
        array $recordData,
        array $values,
    ): mixed {
        if (array_key_exists($fieldName, $values)) {
            return $values[$fieldName];
        }

        if (array_key_exists($fieldName, $recordData)) {
            return $recordData[$fieldName];
        }

        if (array_key_exists('default', $node) && $node['default'] !== null) {
            return $node['default'];
        }

        if (array_key_exists('default', $field)) {
            return $field['default'];
        }

        return null;
    }

    protected function resolveError(string $fieldName, array $errors): ?string
    {
        if (!array_key_exists($fieldName, $errors)) {
            return null;
        }

        $error = $errors[$fieldName];

        if (is_array($error)) {
            return isset($error[0]) ? (string) $error[0] : null;
        }

        if (is_string($error)) {
            return $error;
        }

        return null;
    }

    protected function normalizeRecord(array|object|null $record): array
    {
        if ($record === null) {
            return [];
        }

        if (is_array($record)) {
            return $record;
        }

        if ($record instanceof \JsonSerializable) {
            $data = $record->jsonSerialize();
            return is_array($data) ? $data : [];
        }

        if (method_exists($record, 'toArray')) {
            $data = $record->toArray();
            return is_array($data) ? $data : [];
        }

        return get_object_vars($record);
    }

    protected function humanize(string $value): string
    {
        return str($value)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }
}