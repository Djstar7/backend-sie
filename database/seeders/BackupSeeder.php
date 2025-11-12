<?php

namespace Database\Seeders;

use App\Models\Backup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Backup::factory()->count(20)->create();
    }
}