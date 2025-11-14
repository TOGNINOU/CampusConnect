<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_conflicting_reservation_is_prevented()
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $room = Room::create(['name' => 'Salle Conflit']);

        // Create existing reservation
        Reservation::create([
            'user_id' => $user->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'start_at' => now()->addDays(1),
            'end_at' => now()->addDays(2),
            'status' => Reservation::STATUS_APPROVED,
        ]);

        // Attempt overlapping reservation
        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'reservable_type' => 'room',
            'reservable_id' => $room->id,
            'start_at' => now()->addDays(1)->addHours(1)->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(2)->addHours(-1)->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors('start_at');

        $this->assertDatabaseCount('reservations', 1);
    }
}
