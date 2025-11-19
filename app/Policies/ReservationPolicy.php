<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
class ReservationPolicy
{
    /**
     * Détermine si l'utilisateur peut créer des réservations.
     */
    public function create(User $user): bool
    {
        // Seuls les enseignants et les administrateurs peuvent créer des réservations. Les étudiants peuvent seulement consulter la disponibilité.
        return in_array($user->role, ['teacher', 'admin']);
    }

    /**
     * Détermine si l'utilisateur peut consulter la réservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        // Un administrateur peut voir n'importe quelle réservation ; le propriétaire peut voir sa propre réservation
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    /**
     * Détermine si l'utilisateur peut valider/rejeter la réservation.
     */
    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin();
    }
}
