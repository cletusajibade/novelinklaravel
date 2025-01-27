<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'payment_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'status',
        'location',
        'notes',
        'reminder_at',
        'cancellation_reason',
        'unique_token'
    ];
}
