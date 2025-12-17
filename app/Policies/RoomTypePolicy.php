<?php

namespace App\Policies;

use App\Models\RoomType;
use App\Models\Role;
use App\Models\User;

class RoomTypePolicy
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
    public function view(User $user, RoomType $roomType): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isReceptionist();
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, RoomType $roomType): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, RoomType $roomType): bool
    {
        return $user->isAdmin();
    }
}
