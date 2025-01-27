<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Payment;
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
            'payment_id' => Payment::inRandomOrder()->first()->id,
            'appointment_date' => fake()->dateTime(),
            'appointment_time' => fake()->time(),
            'duration' => fake()->randomElement([15, 30, 45, 60, 120]),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'location' => fake()->randomElement(['Zoom']),
            'notes' => fake()->paragraph(4),
            'reminder_at' => fake()->dateTime(),
            'cancellation_reason'=> fake()->text(),
            'unique_token' => fake()->uniqid()
        ];
    }
}
