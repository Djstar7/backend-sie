<?php

namespace Database\Factories;

use App\Models\Appoitment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['appoitment', 'connexion', 'register', 'reminder', 'alert'];
        $statuses = ['sent', 'delivered', 'failed'];

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'type' => $this->faker->randomElement($types),
            'content' => $this->faker->sentence(10, true),
            'status' => $this->faker->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
