<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = ['create', 'update', 'delete', 'login', 'logout', 'view'];

        return [
            'user_id' => User::inRandomOrder()->value('id'), // user random existant
            'action' => $this->faker->randomElement($actions),
            'description' => $this->faker->sentence(6, true), // phrase explicative
            'address' => $this->faker->ipv4(), // IP address (si tu voulais "address" en fait)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}