<?php

namespace Database\Factories;

use App\Models\VisaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appoitment>
 */
class AppoitmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'rescheduled', 'completed', 'canceled'];

        return [
            'visa_request_id' => VisaRequest::inRandomOrder()->value('id'),
            'scheduled_at' => $this->faker->dateTimeBetween('+1 days', '+1 month'), // rendez-vous dans le futur proche
            'status' => $this->faker->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}