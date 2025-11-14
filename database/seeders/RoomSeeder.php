<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create(['name' => 'Amphi A', 'capacity' => 120, 'location' => 'Bâtiment 1']);
        Room::create(['name' => 'Salle 101', 'capacity' => 30, 'location' => 'Bâtiment 2']);
        Room::create(['name' => 'Salle informatique', 'capacity' => 25, 'location' => 'Bâtiment 3']);
    }
}
