<?php

namespace Database\Factories;

use App\Models\Client;
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
        return [
            'uuid' => fake()->uuid(),
            'client_id' => Client::inRandomOrder()->first()->id,
            'stripe_payment_id' => fake()->randomNumber(5, true),
            'amount' => fake()->randomNumber(3, true),
            'currency' => fake()->currencyCode(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'confirmation_no' => fake()->randomNumber(7, true),

        ];
    }
}
