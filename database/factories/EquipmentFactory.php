<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $equipments = [
            'Sans équipement',
            'Haltères',
            'Barre de traction',
            'Barre olympique',
            'Tapis de sol',
            'Corde à sauter',
            'Élastiques de résistance',
            'Banc de musculation',
            'Vélo stationnaire',
            'Ballon de fitness',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($equipments),
        ];
    }
}
