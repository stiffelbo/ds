<?php

namespace App\UI\Types;

enum FieldInput: string
{
    case Text = 'text';
    case Email = 'email';
    case Password = 'password';
    case Textarea = 'textarea';
    case Number = 'number';
    case Select = 'select';
    case Bool = 'bool';
    case Date = 'date';
}