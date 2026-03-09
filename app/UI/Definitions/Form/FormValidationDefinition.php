<?php

namespace App\UI\Definitions\Form;

final class FormValidationDefinition
{
    /**
     * @param array<int, string> $rules
     * @param array<string, string> $messages
     */
    public function __construct(
        public readonly array $rules = [],
        public readonly array $messages = [],
        public readonly array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'rules' => array_values($this->rules),
            'messages' => $this->messages,
            'meta' => $this->meta,
        ];
    }
}