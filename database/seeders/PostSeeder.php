<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $users->each(function ($user) {
            $posts = Post::factory(3)->create([
                'user_id' => $user->getKey(),
            ]);

            $posts->each(function (Post $post) use ($user) {
                Comment::factory(2)->create([
                    'user_id' => $user->getKey(),
                    'post_id' => $post->getKey(),
                ]);
            });
        });
    }
}
