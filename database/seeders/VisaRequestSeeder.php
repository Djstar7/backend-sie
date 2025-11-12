<?php

namespace Database\Seeders;

use App\Models\VisaRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisaRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VisaRequest::factory()->count(20)->create();
    }
}