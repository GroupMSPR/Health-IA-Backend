<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipments = [
            'Sans équipement',
            'Haltères',
            'Barre de traction',
            'Barre olympique',
            'Corde à sauter',
            'Élastiques de résistance',
            'Banc de musculation',
            'Vélo stationnaire',
            'Ballon de fitness',
        ];

        foreach ($equipments as $equipment) {
            Equipment::firstOrCreate(['name' => $equipment]);
        }
    }
}
