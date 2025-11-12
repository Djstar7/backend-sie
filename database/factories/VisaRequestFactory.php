<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisaRequest>
 */
class VisaRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'approved', 'rejected'];

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'visa_type_id' => VisaType::inRandomOrder()->value('id'),
            'origin_country_id' => Country::inRandomOrder()->value('id'),
            'destination_country_id' => Country::inRandomOrder()->value('id'),
            'status' => $this->faker->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
