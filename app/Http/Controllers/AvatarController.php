<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function updateAvatar(Request $request): JsonResponse
    {
        $image = $request->image;
        $disk = Storage::disk('public');

        $user = User::where('id', '=', $request->user()->id)->firstOrFail();
        $pp = $user->profile_picture;

        $newPath = 'avatars/'.$image->getClientOriginalName();

        if ($pp) {
            $disk->delete($pp);
        }
        $disk->put($newPath, file_get_contents($image));

        $user->update([
            'profile_picture' => $newPath,
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'image' => $newPath,
        ]);

    }
}
