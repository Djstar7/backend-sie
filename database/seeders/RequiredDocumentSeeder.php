<?php

namespace Database\Seeders;

use App\Models\RequiredDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequiredDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequiredDocument::factory()->count(20)->create();
    }
}