<?php

namespace Database\Seeders;

use App\Models\CountryVisaType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryVisaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CountryVisaType::factory()->count(20)->create();
    }
}
