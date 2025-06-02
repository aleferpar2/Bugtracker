<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'guard_name',
    ];

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Give permissions to the role.
     */
    public function givePermissionTo(string|array $permissions): void
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        $permissionIds = Permission::whereIn('name', $permissions)
            ->pluck('id')
            ->toArray();

        $this->permissions()->syncWithoutDetaching($permissionIds);
    }

    /**
     * Revoke permissions from the role.
     */
    public function revokePermissionTo(string|array $permissions): void
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        $permissionIds = Permission::whereIn('name', $permissions)
            ->pluck('id')
            ->toArray();

        $this->permissions()->detach($permissionIds);
    }

    /**
     * Sync permissions for the role.
     */
    public function syncPermissions(string|array $permissions): void
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        $permissionIds = Permission::whereIn('name', $permissions)
            ->pluck('id')
            ->toArray();

        $this->permissions()->sync($permissionIds);
    }
} 