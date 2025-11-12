<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipt>
 */
class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_id' => Payment::inRandomOrder()->value('id'), // lien vers payment existant
            'file_path' => 'receipts/' . $this->faker->uuid() . '.pdf', // chemin fictif vers un PDF
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}