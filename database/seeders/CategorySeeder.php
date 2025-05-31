<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'description' => 'Latest tech news and tutorials'],
            ['name' => 'Travel', 'description' => 'Travel guides and experiences'],
            ['name' => 'Food', 'description' => 'Recipes and restaurant reviews'],
            ['name' => 'Lifestyle', 'description' => 'Tips for better living'],
            ['name' => 'Business', 'description' => 'Business news and insights'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
