<?php

namespace Tests\Feature;

use App\Listeners\UpdateUserWeightAndBmi;
use App\Models\HealthMetric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserWeightAndBmiTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Poids et BMI mis à jour correctement
     *
     * User : 63kg, 170cm
     * BMI attendu = 63 / (1.70²) = 63 / 2.89 = 21.80
     */
    public function test_weight_and_bmi_are_updated_when_health_metric_created(): void
    {
        $user = User::factory()->create([
            'weight' => 50,
            'height' => 170,
            'bmi' => 0,
        ]);

        $this->actingAs($user, 'sanctum');

        $metric = new HealthMetric();
        $metric->weight = 63;

        $listener = new UpdateUserWeightAndBmi();
        $listener->handle($metric);

        $user->refresh();

        $this->assertEquals(63, $user->weight);

        $expectedBmi = round(63 / (1.70 ** 2), 2);
        $this->assertEquals($expectedBmi, $user->bmi);
    }

    /******
     *
     * Si weight est null -> rien se passe
     *****/
    public function test_nothing_happens_when_weight_is_null(): void
    {
        $user = User::factory()->create([
            'weight' => 50,
            'bmi'    => 20,
        ]);

        $this->actingAs($user, 'sanctum');

        $metric = new HealthMetric();
        $metric->weight = null;

        $listener = new UpdateUserWeightAndBmi();
        $listener->handle($metric);

        $user->refresh();

        $this->assertEquals(50, $user->weight);
        $this->assertEquals(20, $user->bmi);
    }

    /******
     *
     * Si height est 0 -> BMI non calculé mais poids mis a jour
     *****/

    public function test_weight_updated_but_bmi_not_calculated_when_height_is_zero(): void
    {
        $user = User::factory()->create([
            'weight' => 50,
            'height' => 0,
            'bmi' => 0,
        ]);

        $this->actingAs($user, 'sanctum');

        $metric = new HealthMetric();
        $metric->weight = 70;

        $listener = new UpdateUserWeightAndBmi();
        $listener->handle($metric);

        $user->refresh();

        $this->assertEquals(70, $user->weight);
        $this->assertEquals(0, $user->bmi);
    }

    public function test_nothing_happens_when_no_authenticated_user(): void
    {
        $metric = new HealthMetric();
        $metric->weight = 70;

        $listener = new UpdateUserWeightAndBmi();

        $listener->handle($metric);

        $this->assertTrue(true);
    }
}
