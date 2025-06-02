<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissions
{
    /**
     * Get all permissions for the user.
     */
    public function getAllPermissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->getAllPermissions()
            ->contains('name', $permission);
    }

    /**
     * Check if the user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->getAllPermissions()
            ->whereIn('name', $permissions)
            ->isNotEmpty();
    }

    /**
     * Check if the user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->getAllPermissions()
            ->whereIn('name', $permissions)
            ->count() === count($permissions);
    }

    /**
     * Give permissions to the user.
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
     * Revoke permissions from the user.
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
     * Sync permissions for the user.
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