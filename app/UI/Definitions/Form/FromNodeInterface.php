<?php

namespace App\UI\Definitions\Form;

interface FormNodeDefinition
{
    public function nodeType(): string;

    public function toArray(): array;
}