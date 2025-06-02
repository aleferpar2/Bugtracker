<?php

namespace App\Policies;

use App\Models\Solution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolutionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Solution $solution): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Solution $solution): bool
    {
        return $user->id === $solution->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Solution $solution): bool
    {
        return $user->id === $solution->user_id || 
               $user->id === $solution->post->user_id || 
               $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Solution $solution): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Solution $solution): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the status of the solution.
     */
    public function updateStatus(User $user, Solution $solution): bool
    {
        return $user->id === $solution->post->user_id || $user->hasRole('admin');
    }
} 