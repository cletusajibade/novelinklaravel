<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
            'appointment_date' => fake()->dateTime(),
            'duration' => fake()->randomElement([15, 30, 45, 60, 120]),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'location' => fake()->randomElement(['Zoom']),
            'notes' => fake()->paragraph(4),
            'reminder_at' => fake()->dateTime()
        ];
    }
}
