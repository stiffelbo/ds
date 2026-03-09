<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPage extends Model
{
    protected $fillable = [
        'user_id',
        'page_id',

        'can_get',
        'can_post',
        'can_patch',
        'can_delete',
        'is_admin',

        'view_restricted_fields',
        'edit_restricted_fields',

        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'can_get'    => 'boolean',
            'can_post'   => 'boolean',
            'can_patch'  => 'boolean',
            'can_delete' => 'boolean',
            'is_admin'   => 'boolean',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function canAccessMethod(string $method): bool
    {
        if ($this->is_admin) {
            return true;
        }

        return match (strtoupper($method)) {
            'GET', 'HEAD' => (bool) $this->can_get,
            'POST'        => (bool) $this->can_post,
            'PUT', 'PATCH' => (bool) $this->can_patch,
            'DELETE'      => (bool) $this->can_delete,
            default       => false,
        };
    }

    public function getViewRestrictedFieldsArray(): array
    {
        if (empty($this->view_restricted_fields)) {
            return [];
        }

        return array_values(array_filter(array_map(
            'trim',
            explode(',', $this->view_restricted_fields)
        )));
    }

    public function getEditRestrictedFieldsArray(): array
    {
        if (empty($this->edit_restricted_fields)) {
            return [];
        }

        return array_values(array_filter(array_map(
            'trim',
            explode(',', $this->edit_restricted_fields)
        )));
    }
}