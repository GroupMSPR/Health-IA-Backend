<?php

namespace Tests\Unit\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_avatar_with_valid_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $image = UploadedFile::fake()->image('avatar.jpg', 100, 100);
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/update-avatar', [
                'image' => $image,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'image',
            ]);

        $expectedPath = 'avatars/'.$user->getKey().'.jpg';

        Storage::disk('public')->assertExists($expectedPath);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'profile_picture' => $expectedPath,
        ]);
    }

    public function test_update_avatar_rejects_non_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('malware.php', 10, 'text/x-php');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/update-avatar', [
                'image' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_update_avatar_unauthenticated(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/update-avatar', [
            'image' => $image,
        ]);

        $response->assertStatus(401);
    }

    public function test_old_avatar_is_deleted_when_updating(): void
    {
        \Storage::fake('public');

        $user = User::factory()->create(['profile_picture' => 'avatars/ancien_avatar.jpg']);

        Storage::disk('public')->put('avatars/ancien_avatar.jpg', 'contenu');

        $newImage = UploadedFile::fake()->image('nouveau_avatar.jpg');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/update-avatar', [
                'image' => $newImage,
            ]);

        Storage::disk('public')->assertMissing('avatars/ancien_avatar.jpg');

        Storage::disk('public')->assertExists('avatars/'.$user->getKey().'.jpg');
    }
}
