<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {

        $genders = ['male', 'female'];
        $statuses = ['single', 'married', 'divorced', 'widowed'];
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement($genders),
            'date_of_birth' => $this->faker->date('Y-m-d'),
            'place_of_birth' => $this->faker->city(),
            'status_mat' => $this->faker->randomElement($statuses),
            'country_id' => Country::inRandomOrder()->value('id'), // récupère un country_id random ou null si aucun pays en bdd
        ];
    }
}
