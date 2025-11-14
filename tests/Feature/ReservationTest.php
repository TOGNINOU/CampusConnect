<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_reservation()
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $room = Room::create(['name' => 'Salle Test']);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'reservable_type' => 'room',
            'reservable_id' => $room->id,
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_create_reservation()
    {
        $user = User::factory()->create(['role' => 'student']);
        $room = Room::create(['name' => 'Salle Test']);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'reservable_type' => 'room',
            'reservable_id' => $room->id,
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('reservations', [
            'user_id' => $user->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
        ]);
    }

    public function test_teacher_can_create_equipment_reservation()
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $equipment = Equipment::create(['name' => 'Projecteur', 'quantity' => 2]);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'reservable_type' => 'equipment',
            'reservable_id' => $equipment->id,
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'reservable_type' => Equipment::class,
            'reservable_id' => $equipment->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_create_equipment_reservation()
    {
        $user = User::factory()->create(['role' => 'student']);
        $equipment = Equipment::create(['name' => 'Projecteur', 'quantity' => 2]);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'reservable_type' => 'equipment',
            'reservable_id' => $equipment->id,
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('reservations', [
            'user_id' => $user->id,
            'reservable_type' => Equipment::class,
            'reservable_id' => $equipment->id,
        ]);
    }

    public function test_admin_can_approve_reservation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'student']);
        $room = Room::create(['name' => 'Salle Test 2']);

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->put(route('reservations.update', $reservation), [
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'approved',
            'admin_id' => $admin->id,
        ]);
    }
}
