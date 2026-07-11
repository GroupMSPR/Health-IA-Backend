<?php

namespace Tests\Unit\Http;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_reset_link_with_valid_email(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'diana@test.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'diana@test.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Si un compte correspond à cette adresse, un lien de réinitialisation a été envoyé.',
            ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_send_reset_link_with_invalid_email_format(): void
    {
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'not email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_send_reset_link_with_nonexistent_email_returns_generic_200(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'inexistant@test.com',
        ]);

        // Anti-enumeration: same 200 + message as an existing email, no mail sent.
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Si un compte correspond à cette adresse, un lien de réinitialisation a été envoyé.',
            ]);

        Notification::assertNothingSent();
    }

    public function test_forgot_password_response_is_identical_for_known_and_unknown_email(): void
    {
        Notification::fake();
        User::factory()->create(['email' => 'known@test.com']);

        $known = $this->postJson('/api/forgot-password', ['email' => 'known@test.com']);
        $unknown = $this->postJson('/api/forgot-password', ['email' => 'unknown@test.com']);

        // The endpoint must not leak which email is registered.
        $this->assertSame($known->status(), $unknown->status());
        $this->assertSame($known->json('message'), $unknown->json('message'));
    }

    public function test_send_reset_link_without_email(): void
    {
        $response = $this->postJson('/api/forgot-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'diana@test.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $token = app('auth.password.broker')->createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => 'diana@test.com',
            'password' => 'nouveau_password',
            'password_confirmation' => 'nouveau_password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Votre mot de passe a été réinitialisé avec succès.',
            ]);

        $user->refresh();
        $this->assertTrue(Hash::check('nouveau_password', $user->password),
            'Le mot de passe doit avoir été mis a jour en BDD');

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'diana@test.com',
        ]);
    }

    public function test_reset_password_with_invalid_token(): void
    {
        User::factory()->create([
            'email' => 'diana@test.com',
        ]);

        $response = $this->postJson('/api/reset-password', [
            'token' => 'token-faux',
            'email' => 'diana@test.com',
            'password' => 'nouveau_password',
            'password_confirmation' => 'nouveau_password',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Le token est invalide ou a expiré.',
            ]);
    }

    public function test_reset_password_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/reset-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token', 'email', 'password']);
    }

    public function test_reset_password_fails_with_short_password(): void
    {
        $user = User::factory()->create(['email' => 'diana@test.com']);
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => 'diana@test.com',
            'password' => 123,
            'password_confirmation' => 123,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

    }
}
