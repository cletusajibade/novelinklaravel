<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    /** @use HasFactory<\Database\Factories\TimeSlotFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'duration',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'blocked',
        'status'
    ];
}
