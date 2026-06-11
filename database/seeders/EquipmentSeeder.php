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
            // Poids du corps & Bases
            'Sans équipement',
            'Tapis de sol',

            // Poids libres
            'Haltères',
            'Kettlebell',
            'Barre olympique',
            'Barre EZ',
            'Disques de poids',
            'Médecine ball',
            'Sacs de sable (Sandbag)',

            // Structures et Bancs
            'Banc de musculation',
            'Banc inclinable',
            'Cage à squat (Rack)',
            'Chaise romaine',
            'Barre de traction',
            'Barres parallèles (Dips)',
            'Anneaux de gymnastique',

            // Machines de musculation
            'Poulie vis-à-vis',
            'Machine Smith',
            'Presse à cuisses',
            'Machine Leg Extension',
            'Machine Leg Curl',
            'Machine Pec Deck',
            'Machine Tirage Dos (Lat Pulldown)',

            // Accessoires & Résistance
            'Élastiques de résistance',
            'Sangles de suspension (TRX)',
            'Ballon de fitness (Swiss Ball)',
            'Bosu Ball',
            'Step',
            'Roulette à abdos (Ab Wheel)',
            'Ceinture lestée',
            'Gilets lestés',
            'Blocs de yoga',

            // Cardio
            'Corde à sauter',
            'Tapis de course',
            'Vélo stationnaire',
            'Vélo elliptique',
            'Rameur',
            'Air Bike',
            'Simulateur d\'escaliers (Stairmaster)'
        ];

        foreach ($equipments as $equipment) {
            Equipment::firstOrCreate(['name' => $equipment]);
        }
    }
}
