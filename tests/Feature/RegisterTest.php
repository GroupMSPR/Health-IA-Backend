<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Doe',
            'first_name' => 'Jane',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'birthdate' => '1990-01-01',
            'gender' => 'Femme',
            'weight' => 70,
            'height' => 175,
            'body_fat_pct' => 20,
            'physical_activity_level' => 'moyennement actif(ve)',
            'daily_caloric_intake' => 2000,
        ], $overrides);
    }

    public function test_register_with_valid_data_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload());

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'informations']);
    }

    public function test_register_calculates_bmi_correctly(): void
    {
        $this->postJson('/api/register', $this->validPayload([
            'weight' => 70,
            'height' => 175,
        ]));

        $user = User::where('email', 'jane.doe@example.com')->firstOrFail();
        $expectedBmi = 70 / ((175 / 100) ** 2);

        $this->assertEquals(round($expectedBmi, 2), round($user->bmi, 2));
    }

    public function test_register_with_missing_required_fields_returns_422(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name', 'last_name', 'email', 'password',
                'birthdate', 'gender', 'weight', 'height', 'body_fat_pct',
                'physical_activity_level', 'daily_caloric_intake',
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
            'password_confirmation' => 'mot-de-passe-different',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_with_invalid_gender_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'gender' => 'alien',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);
    }

    public function test_register_with_invalid_birthdate_returns_422(): void
    {
        $response = $this->postJson('/api/register', $this->validPayload([
            'birthdate' => 'not-a-date',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['birthdate']);
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
