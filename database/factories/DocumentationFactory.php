<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documentation>
 */
class DocumentationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = [
            [
                'heading' => $this->faker->sentence(3),
                'body' => $this->faker->paragraphs(2, true),
            ],
            [
                'heading' => $this->faker->sentence(4),
                'body' => $this->faker->paragraphs(3, true),
            ],
        ];

        return [
            'title' => $this->faker->unique()->sentence(6, true),
            'content' => json_encode($content),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
