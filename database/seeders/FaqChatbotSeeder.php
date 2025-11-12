<?php

namespace Database\Seeders;

use App\Models\FaqChabot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FaqChabot::factory()->count(20)->create();
    }
}