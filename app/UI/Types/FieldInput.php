<?php

namespace App\UI\Enums;

enum FieldInput: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Number = 'number';
    case Select = 'select';
    case Bool = 'bool';
    case Date = 'date';
}