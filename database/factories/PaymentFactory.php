<?php

namespace Database\Factories;

use App\Models\VisaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $methods = ['mtn', 'orange', 'visa', 'paypal'];
        $currencies = ['XAF', 'USD', 'EUR', 'GBP', 'CAD', 'NGN', 'AUD', 'JPY', 'CHF', 'CNY', 'GHS', 'ZAR', 'KES', 'UGX', 'TZS', 'EGP', 'SAR', 'AED'];
        $statuses = ['pending', 'delete', 'failed', 'processing', 'completed', 'canceled', 'expired'];

        return [
            'visa_request_id' => VisaRequest::inRandomOrder()->value('id'),
            'amount' => $this->faker->randomFloat(2, 1000, 500000), // entre 1 000 et 500 000 (ajuste selon besoin)
            'transaction_id' => strtoupper($this->faker->bothify('TXN#####??')), // ID unique formaté TXN12345AB
            'method' => $this->faker->randomElement($methods),
            'currency' => $this->faker->randomElement($currencies),
            'status' => $this->faker->randomElement($statuses),
            'meta' => [
                'age' => $this->faker->numberBetween(18, 60),
                'city' => $this->faker->city(),
                'country' => $this->faker->country(),
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
