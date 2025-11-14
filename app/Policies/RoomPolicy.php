<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    /**
     * Determine whether the user can view the room.
     */
    public function view(?User $user, Room $room): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create rooms.
     */
    public function create(User $user): bool
    {
        // Only administrators may create rooms. Teachers should only make reservations.
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the room.
     */
    public function update(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the room.
     */
    public function delete(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }
}
