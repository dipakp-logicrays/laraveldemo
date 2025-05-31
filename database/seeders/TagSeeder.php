<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React',
            'Tutorial', 'Tips', 'News', 'Review', 'Guide',
            'Beginner', 'Advanced', 'Database', 'API', 'Security'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
