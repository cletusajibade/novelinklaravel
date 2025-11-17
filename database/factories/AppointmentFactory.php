<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Payment;
use App\Models\TimeSlot;
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
            'uuid' => uniqid(),
            'client_id' => Client::inRandomOrder()->first()->id,
            'payment_id' => Payment::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'confirmation_no' => fake()->randomNumber(7, true),
            'location' => fake()->randomElement(['Zoom']),
            'notes' => fake()->paragraph(4),
            'reminder_at' => fake()->dateTime(),
            'cancellation_reason'=> fake()->text()
        ];
    }
}
