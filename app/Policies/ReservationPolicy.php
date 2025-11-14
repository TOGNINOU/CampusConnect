<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
class ReservationPolicy
{
    /**
     * Determine whether the user can create reservations.
     */
    public function create(User $user): bool
    {
        // Only teachers and admins can create reservations. Students may only consult availability.
        return in_array($user->role, ['teacher', 'admin']);
    }

    /**
     * Determine whether the user can view the reservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        // admin can view any reservation, owner can view their reservation
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    /**
     * Determine whether the user can approve/reject the reservation.
     */
    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin();
    }
}
