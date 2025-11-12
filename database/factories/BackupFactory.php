<?php

namespace Database\Factories;

use App\Models\VisaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Backup>
 */
class BackupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visa_request_id' => VisaRequest::inRandomOrder()->value('id'), // lien vers visa_request existant
            'file_path' => 'backups/' . $this->faker->uuid() . '.zip',     // chemin fictif vers fichier backup
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
