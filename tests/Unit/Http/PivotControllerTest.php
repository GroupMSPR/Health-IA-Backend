<?php

namespace Tests\Unit\Http;

use App\Models\Exercise;
use App\Models\Food;
use App\Models\Post;
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

    public function test_like_post_registers_like_and_sets_count(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['like_count' => 0]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/like', ['post_id' => $post->getKey()]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Post liké']);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->getKey(),
            'post_id' => $post->getKey(),
        ]);
        $this->assertEquals(1, $post->fresh()->like_count);
    }

    public function test_liking_twice_unlikes_and_decrements(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['like_count' => 0]);

        $this->actingAs($user, 'sanctum')->postJson('/api/like', ['post_id' => $post->getKey()]);
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/like', ['post_id' => $post->getKey()]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Post unliké']);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->getKey(),
            'post_id' => $post->getKey(),
        ]);
        $this->assertEquals(0, $post->fresh()->like_count);
    }

    public function test_like_count_self_heals_from_actual_likers(): void
    {
        $user = User::factory()->create();
        // Start from a drifted counter to prove the recompute self-heals it.
        $post = Post::factory()->create(['like_count' => 99]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/like', ['post_id' => $post->getKey()]);

        $this->assertEquals(1, $post->fresh()->like_count);
    }

    public function test_like_post_with_nonexistent_post_returns_422(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/like', ['post_id' => '00000000-0000-0000-0000-000000000000']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['post_id']);
    }

    public function test_like_post_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $response = $this->postJson('/api/like', ['post_id' => $post->getKey()]);

        $response->assertStatus(401);
    }
}
