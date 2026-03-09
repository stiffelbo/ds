<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    protected $fillable = [
        'name',
        'endpoint',
        'frontend_url',

        'group_key',
        'group_label',
        'menu_order',

        'label',
        'parent_id',

        'is_menu',
        'is_active',
        'requires_auth',

        'log_requests',
        'cache_days',

        'description',
        'settings',
    ];

    protected $casts = [
        'is_menu'       => 'boolean',
        'is_active'     => 'boolean',
        'requires_auth' => 'boolean',
        'log_requests'  => 'boolean',

        'menu_order' => 'integer',
        'cache_days' => 'integer',

        'settings' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACL RELATIONS
    |--------------------------------------------------------------------------
    */

    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPage::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_pages')
            ->withPivot([
                'can_get',
                'can_post',
                'can_patch',
                'can_delete',
                'is_admin'
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | MENU TREE
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->orderBy('menu_order');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMenu($query)
    {
        return $query->where('is_menu', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}