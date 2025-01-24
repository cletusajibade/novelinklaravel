<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->date(),
            'country' => fake()->country(),
            'country_of_residence' => fake()->country(),
            'consultation_package' => json_encode(['1', '3', '5']),
            'registration_status' => 'step_1/4_completed'
        ];
    }
}
