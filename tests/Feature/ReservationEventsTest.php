<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_endpoint_returns_json()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = Room::create(['name' => 'Salle Evts']);

        Reservation::create([
            'user_id' => $admin->id,
            'reservable_type' => Room::class,
            'reservable_id' => $room->id,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDays(2),
            'status' => Reservation::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($admin)->getJson(route('reservations.events'));

        $response->assertStatus(200)
                 ->assertJsonStructure([[
                     'id', 'title', 'start', 'end', 'color'
                 ]]);
    }
}
