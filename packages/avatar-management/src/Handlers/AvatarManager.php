<?php

namespace MSPR3\AvatarManagement\Handlers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;

class AvatarManager
{
    /**
     * Generate and store SVG avatar when a user is created.
     *
     *
     * @throws Exception
     */
    public function createAvatar(User $user): void
    {
        dd();
        $initials = strtoupper(substr($user->first_name, 0, 1))
            .strtoupper(substr($user->last_name, 0, 1));
        $svg = $this->createSvgAvatar($initials);
        $fileName = 'avatars'.'/'.$user->id.'.svg';
        Storage::disk('public')->put($fileName, $svg);
        $user->update(['profile_picture' => $fileName]);
    }

    /**
     * Create SVG avatar with user's initials.
     *
     * @param  string  $initials  Initials to be displayed on the avatar.
     * @return string SVG content as a string.
     */
    private function createSvgAvatar(string $initials): string
    {
        $width = 100;
        $height = 100;
        $bgColor = '#00BFFF';
        $textColor = '#FFFFFF';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="{$bgColor}" />
    <text x="50%" y="70%" font-family="Verdana" font-size="60" fill="{$textColor}" text-anchor="middle" alignment-baseline="middle">
        {$initials}
    </text>
</svg>
SVG;
    }
}
