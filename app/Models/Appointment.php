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
        'appointment_date',
        'appointment_time',
        'duration',
        'status',
        'location',
        'notes',
        'reminder_at',
        'cancellation_reason',
        'payment_status'
    ];
}
