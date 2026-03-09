<?php

namespace App\UI\Types;

enum FieldType: string
{
    case String = 'string';
    case Number = 'number';
    case Bool = 'bool';
    case Date = 'date';
    case Fk = 'fk';
}