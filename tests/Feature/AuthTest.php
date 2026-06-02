<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\Middleware\StartSession;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ── Configuration Initiale ───────────────────────────────────────────────

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(Kernel::class)
            ->pushMiddleware(StartSession::class);

        $this->withSession([]);
    }

    public function test_login_with_valid_credentials_returns_token(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/login', [
                'email' => $user->email,
                'password' => 'password123',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user', 'access_token', 'token_type'])
            ->assertJsonFragment(['token_type' => 'Bearer']);
    }

    public function test_login_with_invalid_password_returns_401(): void
    {
        $user = User::factory()->create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Identifiants invalides']);
    }

    public function test_login_with_nonexistent_email_returns_401(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/login', [
                'email' => 'nobody@example.com',
                'password' => 'password',
            ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Identifiants invalides']);
    }

    public function test_login_with_missing_fields_returns_422(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_with_invalid_email_format_returns_422(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/login', [
                'email' => 'not-an-email',
                'password' => 'password',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_logout_with_valid_token_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->withToken($token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Déconnexion réussie']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_without_token_returns_401(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_logout_with_invalid_token_returns_401(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->withToken('invalid-token')
            ->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
