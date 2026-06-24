<?php

namespace App\Listeners;

use App\Models\User;
use MSPR3\AvatarManagement\Facades\AvatarManager;

class HandleUserCreated
{
    /**
     * Handle the event.
     */
    public function handle(User $user): void
    {
        AvatarManager::createAvatar($user);
    }
}
