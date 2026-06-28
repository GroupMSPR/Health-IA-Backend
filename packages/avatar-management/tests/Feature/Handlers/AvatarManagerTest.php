<?php

namespace Tests\Feature\Handlers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MSPR3\AvatarManagement\Facades\AvatarManager;
use Tests\TestCase;

class AvatarManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_svg_avatar_and_updates_the_user_profile_picture(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'profile_picture' => null,
        ]);

        AvatarManager::createAvatar($user);

        $fileName = "avatars/{$user->id}.svg";

        Storage::disk('public')->assertExists($fileName);

        $user->refresh();

        $this->assertEquals($fileName, $user->profile_picture);

        $svg = Storage::disk('public')->get($fileName);

        $this->assertStringContainsString('JD', $svg);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('#00BFFF', $svg);
        $this->assertStringContainsString('#FFFFFF', $svg);
    }

    public function test_it_uses_the_first_letter_of_first_and_last_name(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'first_name' => 'alice',
            'last_name' => 'smith',
        ]);

        AvatarManager::createAvatar($user);

        $svg = Storage::disk('public')->get("avatars/{$user->id}.svg");

        $this->assertStringContainsString('AS', $svg);
    }
}
