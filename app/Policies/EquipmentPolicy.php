<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    /**
     * Determine whether the user can view the equipment.
     */
    public function view(?User $user, Equipment $equipment): bool
    {
        // Any authenticated or guest user can view equipment; we'll allow authenticated users to consult availability.
        return true;
    }

    /**
     * Determine whether the user can create equipment.
     */
    public function create(User $user): bool
    {
        // Only administrators may manage (create) equipments.
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the equipment.
     */
    public function update(User $user, Equipment $equipment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the equipment.
     */
    public function delete(User $user, Equipment $equipment): bool
    {
        return $user->isAdmin();
    }
}
