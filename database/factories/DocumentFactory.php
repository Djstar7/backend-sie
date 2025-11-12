<?php

namespace Database\Factories;

use App\Models\VisaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
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
            'name' => $this->faker->unique()->word() . ' Document',
            'file_path' => 'documents/' . $this->faker->uuid() . '.pdf', // chemin fictif vers un fichier pdf
            'is_validated' => $this->faker->boolean(30), // 30% de chances validé
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
