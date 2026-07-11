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
        ]);

        return response()->json([
            'message' => 'Post bien uploadé',
            'path' => $path,
            'url' => \Storage::disk('public')->url($path),
            'post' => $post,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->getKey()) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'text' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                \Storage::disk('public')->delete($post->image);
            }

            $path = \Storage::disk('public')->putFile('posts/'.$post->getKey(), $request->file('image'));

            $post->image = $path;
        }

        if ($request->has('text')) {
            $post->text = $request->input('text');
        }

        $post->save();

        return response()->json([
            'message' => 'Post mis à jour',
            'post' => $post,
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->getKey()) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        if ($post->image) {
            \Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post supprimé']);
    }
}
