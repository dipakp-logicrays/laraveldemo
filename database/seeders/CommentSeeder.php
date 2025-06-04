<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\CommentVote;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        // Make sure we have users and posts
        if ($users->count() == 0 || $posts->count() == 0) {
            $this->command->info('No users or posts found. Please seed users and posts first.');
            return;
        }

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

        // Add votes to comments
        $this->addVotesToComments($users);
    }

    protected function addVotesToComments($users)
    {
        foreach (Comment::all() as $comment) {
            // Calculate max possible votes (total users minus comment author)
            $availableVoters = $users->count() - 1; // Exclude comment author

            if ($availableVoters <= 0) {
                continue; // Skip if no other users to vote
            }

            // Random number of votes (0 to minimum of 5 or available voters)
            $maxVotes = min(5, $availableVoters);
            $voteCount = rand(0, $maxVotes);

            if ($voteCount == 0) {
                continue; // Skip if no votes
            }

            // Get users who can vote (exclude comment author)
            $potentialVoters = $users->filter(function ($user) use ($comment) {
                return $user->id !== $comment->user_id;
            });

            // Select random voters
            $voters = $potentialVoters->random(min($voteCount, $potentialVoters->count()));

            foreach ($voters as $user) {
                // Create vote
                CommentVote::create([
                    'comment_id' => $comment->id,
                    'user_id' => $user->id,
                    'type' => rand(0, 1) ? 'like' : 'dislike'
                ]);
            }
        }

        $this->command->info('Comments and votes seeded successfully!');
    }
}
