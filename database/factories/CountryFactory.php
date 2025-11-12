<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->unique()->country(),
            "iso_code" => $this->faker->unique()->countryCode(),
            "phone_code" => $this->faker->unique()->phoneNumber(),
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}
