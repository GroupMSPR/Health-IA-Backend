<?php

namespace Tests\Feature\Ai;

use App\Enums\AiPredictionStatus;
use App\Enums\AiPredictionType;
use App\Jobs\AnalyzeMealJob;
use App\Jobs\RecommendJob;
use App\Models\AiPrediction;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use MSPR2\SdkIA\Facade\IAManager;
use Tests\TestCase;

class AiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_analyze_meal_submits_and_queues_a_job(): void
    {
        Storage::fake('public');
        Queue::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/ai/analyze-meal', [
            'image' => UploadedFile::fake()->image('meal.jpg'),
        ]);

        $response->assertStatus(202)
            ->assertJson(['type' => 'analyze_meal', 'status' => 'pending']);

        $this->assertDatabaseHas('ai_predictions', [
            'user_id' => $user->getKey(),
            'type' => 'analyze_meal',
            'status' => 'pending',
        ]);
        Queue::assertPushed(AnalyzeMealJob::class);
    }

    public function test_analyze_meal_requires_an_image(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->postJson('/api/ai/analyze-meal', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_recommend_submits_and_queues_a_job(): void
    {
        Queue::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/ai/recommend', []);

        $response->assertStatus(202)
            ->assertJson(['type' => 'recommend', 'status' => 'pending']);

        Queue::assertPushed(RecommendJob::class);
    }

    public function test_submit_requires_authentication(): void
    {
        $this->postJson('/api/ai/recommend', [])->assertUnauthorized();
        $this->postJson('/api/ai/analyze-meal', [])->assertUnauthorized();
    }

    public function test_show_returns_the_owner_prediction(): void
    {
        $user = User::factory()->create();
        $prediction = AiPrediction::create([
            'user_id' => $user->getKey(),
            'type' => AiPredictionType::Recommend,
            'status' => AiPredictionStatus::Completed,
            'result' => ['status' => 'success'],
        ]);

        $this->actingAs($user, 'sanctum')->getJson("/api/ai/predictions/{$prediction->getKey()}")
            ->assertOk()
            ->assertJson(['id' => $prediction->getKey(), 'status' => 'completed']);
    }

    public function test_show_denies_another_users_prediction(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $prediction = AiPrediction::create([
            'user_id' => $owner->getKey(),
            'type' => AiPredictionType::Recommend,
            'status' => AiPredictionStatus::Pending,
        ]);

        $this->actingAs($other, 'sanctum')->getJson("/api/ai/predictions/{$prediction->getKey()}")
            ->assertNotFound();
    }

    public function test_show_requires_authentication(): void
    {
        $prediction = AiPrediction::create([
            'user_id' => User::factory()->create()->getKey(),
            'type' => AiPredictionType::Recommend,
            'status' => AiPredictionStatus::Pending,
        ]);

        $this->getJson("/api/ai/predictions/{$prediction->getKey()}")->assertUnauthorized();
    }

    public function test_analyze_meal_job_completes_and_cleans_up_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('ai/meals/x/test.jpg', 'binary');
        IAManager::shouldReceive('analyzeMeal')->once()->andReturn(['status' => 'success', 'data' => ['name' => 'Salade']]);

        $prediction = AiPrediction::create([
            'user_id' => User::factory()->create()->getKey(),
            'type' => AiPredictionType::AnalyzeMeal,
            'status' => AiPredictionStatus::Pending,
            'input' => ['image_path' => 'ai/meals/x/test.jpg', 'original_name' => 'test.jpg'],
        ]);

        (new AnalyzeMealJob($prediction->getKey()))->handle();

        $prediction->refresh();
        $this->assertSame(AiPredictionStatus::Completed, $prediction->status);
        $this->assertSame('success', $prediction->result['status']);
        Storage::disk('public')->assertMissing('ai/meals/x/test.jpg');
    }

    public function test_recommend_job_filters_and_completes(): void
    {
        $user = User::factory()->create();
        Exercise::factory()->create(['name' => 'Squat']);

        IAManager::shouldReceive('recommend')->once()->andReturn([
            'is_working' => 1,
            'predictions' => [['exercise' => 'Squat']],
        ]);
        IAManager::shouldReceive('isLegal')->andReturn(true);

        $prediction = AiPrediction::create([
            'user_id' => $user->getKey(),
            'type' => AiPredictionType::Recommend,
            'status' => AiPredictionStatus::Pending,
            'input' => ['favorite_exercise_category' => 'Musculation'],
        ]);

        (new RecommendJob($prediction->getKey()))->handle();

        $prediction->refresh();
        $this->assertSame(AiPredictionStatus::Completed, $prediction->status);
        $this->assertSame('success', $prediction->result['status']);
        $this->assertCount(1, $prediction->result['predictions']);
    }
}
