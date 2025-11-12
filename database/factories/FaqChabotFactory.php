<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaqChabot>
 */
class FaqChabotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['General', 'Account', 'Payment', 'Technical', 'Support'];

        return [
            'question' => $this->faker->sentence(8, true),
            'answer' => $this->faker->paragraph(2, true),
            'category' => $this->faker->randomElement($categories),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
