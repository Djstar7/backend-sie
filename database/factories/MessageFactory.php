<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VisaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['sending', 'sent', 'read', 'failed'];

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'visa_request_id' => VisaRequest::inRandomOrder()->value('id'),
            // 'subject' => $this->faker->sentence(6, true),
            'content' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}