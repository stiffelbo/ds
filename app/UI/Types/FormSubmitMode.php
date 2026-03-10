<?php

namespace App\UI\Types;

enum FormSubmitMode: string
{
    case Manual = 'manual';
    case PerField = 'per_field';
    case Auto = 'auto';
}