<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'text' => 'nullable|string',
        ]);

        $user = $request->user();

        $file = $request->file('image');
        $path = \Storage::disk('public')->putFile('posts/'.$user->getKey(), $file);

        $post = Post::create([
            'user_id' => $user->getKey(),
            'text' => $request->input('text'),
            'image' => $path,
            'like_count' => 0,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'Post bien uploadé',
            'path' => $path,
            'url' => \Storage::disk('public')->url($path),
            'post' => $post,
        ], 201);
    }
}
