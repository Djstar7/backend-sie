<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CountryVisaType>
 */
class CountryVisaTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countryId = Country::inRandomOrder()->value('id');
        $visaTypeId = VisaType::inRandomOrder()->value('id');

        return [
            'country_id' => $countryId,
            'visa_type_id' => $visaTypeId,
            'price_base' => $this->faker->randomFloat(2, 50, 1000), // prix de base entre 50 et 1000
            'price_per_child' => $this->faker->randomFloat(2, 20, 300), // prix enfant entre 20 et 300
            'processing_duration_min' => $this->faker->numberBetween(1, 5), // durée min en jours
            'processing_duration_max' => $this->faker->numberBetween(6, 30), // durée max en jours
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}