<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MSPR2\SdkIA\Handlers\IAManager;
use Tests\TestCase;

class IAControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *Image valide → appel IAManager → 200
     */
    // =================== ANALYZE MEAL ===================

    public function test_analyze_meal_with_valid_image(): void
    {
        \Storage::fake('public');

        $user = User::factory()->create();

        $mock = \Mockery::mock(IAManager::class);
        $mock->shouldReceive('analyzeMeal')
            ->once()
            ->andReturn([
                'status' => 'success',
                'is_working' => 1,
                'data' => ['name' => 'Burger', 'nutrition' => ['calories' => 500]],
            ]);
        $this->app->instance('IAManager', $mock);

        $image = UploadedFile::fake()->image('burger.jpg');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/analyze-meal', [
                'image' => $image,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success']);

    }

    /**
     *Image manquante → 422
     */
    public function test_analyze_meal_without_image(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/analyze-meal', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     *Non authentifié → 401
     */
    public function test_analyze_meal_unauthenticated(): void
    {
        $image = UploadedFile::fake()->image('burger.jpg');

        $response = $this->postJson('/api/ai/analyze-meal', [
            'image' => $image,
        ]);

        $response->assertStatus(401);
    }

    /**
     *IAManager retourne degraded → 200 avec status degraded
     */
    public function test_analyze_meal_returns_degraded_when_service_unavailable(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $mock = \Mockery::mock(IAManager::class);
        $mock->shouldReceive('analyzeMeal')
            ->once()
            ->andReturn([
                'status' => 'degraded',
                'is_working' => 0,
                'data' => null,
                'message' => 'service Llava indisponible',
            ]);
        $this->app->instance('IAManager', $mock);

        $image = UploadedFile::fake()->image('burger.jpg');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/analyze-meal', [
                'image' => $image,
            ]);

        $response->assertStatus(200);
    }

    // =================== RECOMMEND ===================
    /**
     *Recommandations générées avec succes
     */
    public function test_recommend_returns_predictions(): void
    {
        $user = User::factory()->create([
            'bmi' => 22.5,
            'physical_activity_level' => 'active',
            'birthdate' => '2002-04-01',
            'favorite_exercise_category' => 'Cardio',
        ]);

        $mock = \Mockery::mock(IAManager::class);
        $mock->shouldReceive('recommend')
            ->once()
            ->andReturn([
                'status' => 'success',
                'is_working' => 1,
                'predictions' => [
                    ['exercise' => 'Course à pied', 'confidence' => 0.85],
                    ['exercise' => 'Vélo', 'confidence' => 0.72],
                ],
            ]);
        $mock->shouldReceive('isLegal')
            ->andReturn(true);

        $this->app->instance('IAManager', $mock);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/recommend', []);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success']);
    }

    /**
     * Service indisponible → degraded
     */
    public function test_recommend_returns_degraded_when_service_unavailable(): void
    {
        $user = User::factory()->create([
            'bmi' => 22.5,
            'physical_activity_level' => 'active',
            'birthdate' => '1995-01-01',
            'favorite_exercise_category' => 'Cardio',
        ]);

        $mock = \Mockery::mock(IAManager::class);
        $mock->shouldReceive('recommend')
            ->once()
            ->andReturn([
                'status' => 'degraded',
                'is_working' => 0,
                'predictions' => [],
                'message' => 'Service de recommandation indisponible',
            ]);
        $this->app->instance('IAManager', $mock);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/recommend', []);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'degraded']);
    }

    /**
     * Catégorie invalide → 422
     */
    public function test_recommend_with_invalid_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/ai/recommend', [
                'favorite_exercise_category' => 'InvalidCategory',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['favorite_exercise_category']);
    }

}
