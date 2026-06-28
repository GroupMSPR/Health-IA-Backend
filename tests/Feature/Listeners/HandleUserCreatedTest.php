<?php

namespace Tests\Feature\Listeners;

use App\Listeners\HandleUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use MSPR3\AvatarManagement\Handlers\AvatarManager;
use Tests\TestCase;

class HandleUserCreatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_avatar_is_called_when_user_is_created(): void
    {
        $mock = Mockery::mock(AvatarManager::class);
        $mock->shouldReceive('createAvatar')
            ->once()
            ->andReturn(null);

        $this->app->instance('AvatarManager', $mock);

        $user = User::factory()->create();

        $listener = new HandleUserCreated();
        $listener->handle($user);
    }

    public function test_create_avatar_receives_correct_user(): void
    {
        $mock = Mockery::mock(AvatarManager::class);
        $mock->shouldReceive('createAvatar')
            ->once()
            ->andReturn(null);

        $this->app->instance('AvatarManager', $mock);

        $user = User::factory()->create();

        $listener = new HandleUserCreated();
        $listener->handle($user);
    }
}
