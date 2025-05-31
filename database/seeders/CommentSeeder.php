<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        foreach ($posts as $post) {
            // Create 2-5 root comments per post
            $commentCount = rand(2, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                $comment = Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'content' => fake()->paragraph(),
                    'is_approved' => true,
                    'created_at' => fake()->dateTimeBetween($post->created_at, 'now'),
                ]);

                // 50% chance of having replies
                if (rand(0, 1)) {
                    $replyCount = rand(1, 3);
                    for ($j = 0; $j < $replyCount; $j++) {
                        Comment::create([
                            'post_id' => $post->id,
                            'user_id' => $users->random()->id,
                            'parent_id' => $comment->id,
                            'content' => fake()->paragraph(),
                            'is_approved' => true,
                            'created_at' => fake()->dateTimeBetween($comment->created_at, 'now'),
                        ]);
                    }
                }
            }
        }
    }
}
