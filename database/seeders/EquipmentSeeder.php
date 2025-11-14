<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        Equipment::create(['name' => 'Vidéoprojecteur', 'quantity' => 5, 'description' => 'Projecteur portable']);
        Equipment::create(['name' => 'Ordinateur portable', 'quantity' => 10, 'description' => 'PC pour prêt']);
        Equipment::create(['name' => 'Microphone', 'quantity' => 8, 'description' => 'Micro sans fil']);
    }
}
