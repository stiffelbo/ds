<?php

namespace App\UI\Types;

enum TableCellType: string
{
    case Text = 'text';
    case Number = 'number';
    case Boolean = 'boolean';
    case Date = 'date';
    case DateTime = 'datetime';
    case Badge = 'badge';
    case Relation = 'relation';
    case Actions = 'actions';
    case Custom = 'custom';
}