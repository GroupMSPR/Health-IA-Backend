<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $file = $request->file('image');
        $path = \Storage::disk('public')->put('posts', $file);

        return response()->json([
            'message' => 'Image bien uploadé',
            'path' => $path,
            'url' => \Storage::disk('public')->url($path),
        ], 201);
    }
}
