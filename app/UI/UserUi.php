<?php

namespace App\UI;

use App\UI\BaseUi;
use App\UI\Builders\FieldBuilder;
use App\UI\Builders\FormBuilder;
use App\UI\Definitions\Form\FormOptionsDefinition;
use App\UI\Definitions\Form\FormValidationDefinition;
use App\UI\Definitions\Form\OptionItemDefinition;
use App\UI\Types\FieldInput;

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
        return 'User management, authentication data and global ACL role.';
    }

    protected function help(): array
    {
        return [
            'Role is a global ACL override.',
            'Password is used only in create/reset flows.',
            'Remember token is internal session data and is not exposed in UI.',
        ];
    }

    protected function fields(): array
    {
        return [
            'id' => FieldBuilder::make('id')
                ->number()
                ->label('ID')
                ->build(),

            'name' => FieldBuilder::make('name')
                ->string()
                ->label('First name')
                ->required()
                ->build(),

            'last_name' => FieldBuilder::make('last_name')
                ->string()
                ->label('Last name')
                ->required()
                ->build(),

            'email' => FieldBuilder::make('email')
                ->string()
                ->label('Email')
                ->required()
                ->build(),

            'password' => FieldBuilder::make('password')
                ->string()
                ->label('Password')
                ->required()
                ->build(),

            'email_verified_at' => FieldBuilder::make('email_verified_at')
                ->date()
                ->label('Email verified at')
                ->nullable()
                ->build(),

            'role' => FieldBuilder::make('role')
                ->string()
                ->label('Role')
                ->required()
                ->default('viewer')
                ->build(),

            'is_active' => FieldBuilder::make('is_active')
                ->bool()
                ->label('Active')
                ->required()
                ->default(true)
                ->build(),

            'last_login_at' => FieldBuilder::make('last_login_at')
                ->date()
                ->label('Last login at')
                ->nullable()
                ->build(),

            'created_at' => FieldBuilder::make('created_at')
                ->date()
                ->label('Created at')
                ->nullable()
                ->build(),

            'updated_at' => FieldBuilder::make('updated_at')
                ->date()
                ->label('Updated at')
                ->nullable()
                ->build(),
        ];
    }

    protected function forms(): array
    {
        $roleOptions = new FormOptionsDefinition(
            items: [
                new OptionItemDefinition('admin', 'Admin'),
                new OptionItemDefinition('manager', 'Manager'),
                new OptionItemDefinition('viewer', 'Viewer'),
            ]
        );

        return [
            'add' => FormBuilder::make('add', 'Create user')
                ->group('identity', 'Basic identity')
                    ->field(
                        field: 'name',
                        input: FieldInput::Text,
                        label: 'First name',
                        placeholder: 'Enter first name',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'string', 'max:100']
                        ),
                    )
                    ->field(
                        field: 'last_name',
                        input: FieldInput::Text,
                        label: 'Last name',
                        placeholder: 'Enter last name',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'string', 'max:100']
                        ),
                    )
                    ->field(
                        field: 'email',
                        input: FieldInput::Email,
                        label: 'Email',
                        placeholder: 'Enter email',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'email', 'max:255']
                        ),
                    )
                    ->field(
                        field: 'password',
                        input: FieldInput::Password,
                        label: 'Password',
                        placeholder: 'Enter password',
                        helperText: 'Set initial password for the user.',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'string', 'min:8', 'max:255']
                        ),
                    )
                ->end()
                ->group('access', 'Access and status')
                    ->field(
                        field: 'role',
                        input: FieldInput::Select,
                        label: 'Role',
                        required: true,
                        md: 6,
                        options: $roleOptions,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'in:admin,manager,viewer']
                        ),
                    )
                    ->field(
                        field: 'is_active',
                        input: FieldInput::Bool,
                        label: 'Active',
                        required: true,
                        md: 6,
                        default: true,
                    )
                ->end()
                ->build(),

            'edit' => FormBuilder::make('edit', 'Edit user')
                ->group('identity', 'Basic identity')
                    ->field(
                        field: 'name',
                        input: FieldInput::Text,
                        label: 'First name',
                        placeholder: 'Enter first name',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'string', 'max:100']
                        ),
                    )
                    ->field(
                        field: 'last_name',
                        input: FieldInput::Text,
                        label: 'Last name',
                        placeholder: 'Enter last name',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'string', 'max:100']
                        ),
                    )
                    ->field(
                        field: 'email',
                        input: FieldInput::Text,
                        label: 'Email',
                        placeholder: 'Enter email',
                        required: true,
                        md: 6,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'email', 'max:255']
                        ),
                    )
                ->end()
                ->group('access', 'Access and status')
                    ->field(
                        field: 'role',
                        input: FieldInput::Select,
                        label: 'Role',
                        required: true,
                        md: 6,
                        options: $roleOptions,
                        validation: new FormValidationDefinition(
                            rules: ['required', 'in:admin,manager,viewer']
                        ),
                    )
                    ->field(
                        field: 'is_active',
                        input: FieldInput::Bool,
                        label: 'Active',
                        required: true,
                        md: 6,
                    )
                ->end()
                ->group(
                    key: 'audit',
                    label: 'Audit',
                    collapsible: true,
                    collapsed: true,
                )
                    ->field(
                        field: 'email_verified_at',
                        input: FieldInput::Date,
                        label: 'Email verified at',
                        readonly: true,
                        disabled: true,
                        md: 6,
                    )
                    ->field(
                        field: 'last_login_at',
                        input: FieldInput::Date,
                        label: 'Last login at',
                        readonly: true,
                        disabled: true,
                        md: 6,
                    )
                    ->field(
                        field: 'created_at',
                        input: FieldInput::Date,
                        label: 'Created at',
                        readonly: true,
                        disabled: true,
                        md: 6,
                    )
                    ->field(
                        field: 'updated_at',
                        input: FieldInput::Date,
                        label: 'Updated at',
                        readonly: true,
                        disabled: true,
                        md: 6,
                    )
                ->end()
                ->build(),
        ];
    }
}