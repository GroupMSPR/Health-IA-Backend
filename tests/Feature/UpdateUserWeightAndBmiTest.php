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
     * A basic unit test example.
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
}
