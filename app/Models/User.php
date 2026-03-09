<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden attributes
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',

            'is_active' => 'boolean',

            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ACL RELATIONS
    |--------------------------------------------------------------------------
    */

    public function pagePermissions(): HasMany
    {
        return $this->hasMany(UserPage::class);
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'user_pages')
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
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CHECK
    |--------------------------------------------------------------------------
    */

    public function canAccessPage(Page $page, string $method): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permission = $this->pagePermissions()
            ->where('page_id', $page->id)
            ->first();

        if (!$permission) {
            return false;
        }

        if ($permission->is_admin) {
            return true;
        }

        return match (strtoupper($method)) {
            'GET'    => $permission->can_get,
            'POST'   => $permission->can_post,
            'PATCH',
            'PUT'    => $permission->can_patch,
            'DELETE' => $permission->can_delete,
            default  => false,
        };
    }
}