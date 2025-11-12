<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequiredDocument>
 */
class RequiredDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['single', 'married', 'divorced', 'widowed'];

        return [
            'name' => $this->faker->unique()->words(2, true), // 2 mots concaténés pour nom document
            'status_mat' => $this->faker->randomElement($statuses),
            'min_age' => $this->faker->numberBetween(18, 80), // age entre 18 et 80 ans
            'max_age' => $this->faker->numberBetween(18, 80), // age entre 18 et 80 ans
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
