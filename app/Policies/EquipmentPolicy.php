<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter le matériel.
     */
    public function view(?User $user, Equipment $equipment): bool
    {
        // Tout utilisateur (authentifié ou invité) peut consulter le matériel.
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer du matériel.
     */
    public function create(User $user): bool
    {
        // Seuls les administrateurs peuvent gérer/ajouter du matériel.
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
