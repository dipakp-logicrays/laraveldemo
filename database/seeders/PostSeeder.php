<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create a test user if none exists
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::all();
        $tags = Tag::all();

        // Create 20 sample posts
        for ($i = 0; $i < 20; $i++) {
            $post = Post::create([
                'title' => $faker->sentence(6),
                'excerpt' => $faker->paragraph(2),
                'content' => $faker->paragraphs(5, true),
                'category_id' => $categories->random()->id,
                'user_id' => $user->id,
                'status' => $faker->randomElement(['draft', 'published']),
                'published_at' => $faker->randomElement([null, $faker->dateTimeBetween('-1 year', 'now')]),
                'views' => $faker->numberBetween(0, 1000),
            ]);

            // Attach random tags
            $post->tags()->attach(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );
        }
    }
}
