<?php

namespace App\UI\Entities;

use App\UI\BaseUi;
use App\UI\Definitions\FieldDefinition;
use App\UI\Definitions\Form\FormDefinition;
use App\UI\Definitions\Form\FormFieldDefinition;
use App\UI\Definitions\Form\FormFieldsetDefinition;
use App\UI\Types\FieldType;
use App\UI\Types\FormSubmitMode;

class UserUi extends BaseUi
{
    protected function entity(): string
    {
        return 'users';
    }

    protected function title(): ?string
    {
        return 'Users';
    }

    protected function description(): ?string
    {
        return 'User management and access administration.';
    }

    protected function help(): array
    {
        return [
            'This module manages users and their access.',
            'Password fields should only be used in create/reset flows.',
        ];
    }

    protected function fields(): array
    {
        return [
            'id' => new FieldDefinition(
                name: 'id',
                dataType: FieldType::Number,
                label: 'ID',
            ),
            'name' => new FieldDefinition(
                name: 'name',
                dataType: FieldType::String,
                required: true,
                label: 'Name',
            ),
            'email' => new FieldDefinition(
                name: 'email',
                dataType: FieldType::String,
                required: true,
                label: 'Email',
            ),
        ];
    }

    protected function forms(): array
    {
        return [
            'add' => new FormDefinition(
                key: 'add',
                label: 'Create user',
                submitMode: FormSubmitMode::Manual,
                nodes: [
                    new FormFieldsetDefinition(
                        key: 'main',
                        label: 'Basic data',
                        nodes: [
                            new FormFieldDefinition(field: 'name', md: 6, required: true),
                            new FormFieldDefinition(field: 'email', md: 6, required: true),
                        ]
                    ),
                ]
            ),
        ];
    }
}