<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;

class GuestPolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isReceptionist();
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Guest $guest): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isReceptionist();
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isReceptionist();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Guest $guest): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isReceptionist();
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Guest $guest): bool
    {
        return $user->isAdmin() || $user->isManager();
    }
}
