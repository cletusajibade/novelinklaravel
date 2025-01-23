<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'payment_id',
        'amount',
        'currency',
        'status',
        'stripe_customer_id',
        'payment_method_id',
        'payment_method_type',
        'card_brand',
        'card_last4',
        'description',
        'refund_status',
        'refund_amount',
        'dispute_status',
    ];
}
