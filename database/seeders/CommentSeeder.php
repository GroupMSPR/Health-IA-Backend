<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            return;
        }

        $posts->each(function (Post $post) use ($users) {
            Comment::factory(3)->create([
                'user_id' => $users->random()->getKey(),
                'post_id' => $post->getKey(),
            ]);
        });
    }
}
