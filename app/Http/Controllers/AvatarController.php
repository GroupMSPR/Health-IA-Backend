<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $disk = Storage::disk('public');
        $user = $request->user();
        $image = $request->file('image');

        if ($user->profile_picture) {
            $disk->delete($user->profile_picture);
        }

        $newPath = $image->storeAs('avatars', $user->getKey().'.'.$image->extension(), 'public');

        $user->update([
            'profile_picture' => $newPath,
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'image' => $newPath,
            'url' => $disk->url($newPath),
        ]);
    }
}
