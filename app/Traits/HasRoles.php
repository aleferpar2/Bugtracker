<?php

namespace App\Traits;

trait HasRoles
{
    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if the user has all of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        return count(array_intersect([$this->role], $roles)) === count($roles);
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string $role): void
    {
        $this->update(['role' => $role]);
    }
} 