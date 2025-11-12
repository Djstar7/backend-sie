<?php

namespace Database\Seeders;

use App\Models\Appoitment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppoitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appoitment::factory()->count(20)->create();
    }
}