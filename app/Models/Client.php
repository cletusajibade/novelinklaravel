<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'latest_stripe_payment_id',
        'latest_payment_date',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'country',
        'country_of_residence',
        'country_residence_status',
        'marital_status',
        'have_a_passport',
        'passport_expiry_date',
        'passport_country',
        'family_coming_to_canada',
        'highest_education',
        'occupation',
        'years_of_experience',
        'canadian_work_experience',
        'canadian_education',
        'refused_visa',
        'involved_in_genocide',
        'criminal_offence',
        'background_check_details',
        'other_information',
        'consultation_package',
        'registration_status',
        'unique_token'
    ];
}
