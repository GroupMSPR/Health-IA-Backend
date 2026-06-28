<?php

namespace Tests\Unit\Http;

use App\Models\Exercise;
use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PivotControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_consume_food_with_valid_food_id(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $food = Food::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/consume', [
                'food_id' => $food->getKey(),
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Aliment ajouté']);

        $this->assertDatabaseHas('consume', [
            'user_id' => $user->getKey(),
            'food_id' => $food->getKey(),
        ]);
    }

    public function test_consume_food_with_invalid_food_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/consume', [
                'food_id' => '00000000-0000-0000-0000-000000000000',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['food_id']);
    }

    public function test_consume_food_without_food_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/consume', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['food_id']);
    }

    public function test_consume_food_with_invalid_uuid_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/consume', [
                'food_id' => 'pas-un-uuid',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['food_id']);
    }

    public function test_consume_food_unauthenticated(): void
    {
        $food = Food::factory()->create();

        $response = $this->postJson('/api/consume', [
            'food_id' => $food->getKey(),
        ]);

        $response->assertStatus(401);
    }

    public function test_practice_exercise_with_valid_exercise_id(): void
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/practice', [
                'exercise_id' => $exercise->getKey(),
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Exercice ajouté']);

        $this->assertDatabaseHas('practice', [
            'user_id' => $user->getKey(),
            'exercise_id' => $exercise->getKey(),
        ]);
    }

    public function test_practice_exercise_with_nonexistent_exercise_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/practice', [
                'exercise_id' => '00000000-0000-0000-0000-000000000000',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['exercise_id']);
    }

    public function test_practice_exercise_without_exercise_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/practice', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['exercise_id']);
    }

    public function test_practice_exercise_unauthenticated(): void
    {
        $exercise = Exercise::factory()->create();

        $response = $this->postJson('/api/practice', [
            'exercise_id' => $exercise->getKey(),
        ]);

        $response->assertStatus(401);
    }
}
