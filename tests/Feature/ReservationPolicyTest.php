<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_approve_reservation()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $room = Room::create(['name' => 'Salle test']);

        $reservation = Reservation::create([
            'user_id' => $student->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($teacher)->put(route('reservations.update', $reservation), [
            'status' => 'approved',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_cannot_view_others_reservation()
    {
        $userA = User::factory()->create(['role' => 'student']);
        $userB = User::factory()->create(['role' => 'student']);
        $room = Room::create(['name' => 'Salle test']);

        $reservation = Reservation::create([
            'user_id' => $userA->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($userB)->get(route('reservations.show', $reservation));
        $response->assertStatus(403);
    }
}
