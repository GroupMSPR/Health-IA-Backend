<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed> */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'age' => 25,
            'gender' => 'female',
            'weight' => 65,
            'height' => 170,
            'body_fat_pct' => 20,
            'goal' => 'Improve overall fitness',
        ], $overrides);
    }

    // ── Success ──────────────────────────────────────────────────────────────

    public function test_register_with_valid_data_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload());

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'access_token', 'token_type', 'informations'])
            ->assertJsonFragment([
                'message' => 'Utilisateur inscrit avec succès',
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'jane.doe@example.com']);
    }

    public function test_register_calculates_bmi_correctly(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'weight' => 70,
            'height' => 175,
        ]));

        $response->assertStatus(201);

        $expectedBmi = 70 / ((175 / 100) ** 2);

        $user = User::where('email', 'jane.doe@example.com')->firstOrFail();
        $this->assertEqualsWithDelta($expectedBmi, (float) $user->bmi, 0.01);
    }

    public function test_register_sets_subscription_to_free(): void
    {
        $this->postJson('/api/register', $this->validPayload());

        $this->assertDatabaseHas('users', [
            'email' => 'jane.doe@example.com',
            'subscription' => 'free',
        ]);
    }

    // ── Validation failures ───────────────────────────────────────────────────

    public function test_register_with_missing_required_fields_returns_422(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name', 'last_name', 'email', 'password',
                'age', 'gender', 'weight', 'height', 'body_fat_pct', 'goal',
            ]);
    }

    public function test_register_with_duplicate_email_returns_422(): void
    {
        User::factory()->create(['email' => 'jane.doe@example.com']);

        $response = $this->postJson('/api/register', $this->validPayload());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_with_password_not_confirmed_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'password' => 'secret123',
            'password_confirmation' => 'different',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_with_invalid_gender_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'gender' => 'unknown',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);
    }

    public function test_register_with_invalid_age_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'age' => 0,
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['age']);
    }

    public function test_register_with_short_password_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'password' => '123',
            'password_confirmation' => '123',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
