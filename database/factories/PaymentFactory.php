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
            'client_id' => Client::inRandomOrder()->first()->id,
            'payment_id'=> fake()->uuid(),
            'amount'=> fake()->randomNumber(3, true),
            'currency'=> fake()->currencyCode(),
            'status'=> fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled'])
        ];
    }
}
