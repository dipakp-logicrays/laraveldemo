<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faqs')->insert([
            ['question' => 'What is Laravel?', 'answer' => 'Laravel is a PHP framework.'],
            ['question' => 'Is Laravel open source?', 'answer' => 'Yes, it is open source.'],
        ]);
    }
}
