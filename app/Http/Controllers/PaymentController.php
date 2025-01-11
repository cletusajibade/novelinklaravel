<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pay()
    {
        $stripe = new StripeClient(
            'sk_test_51QfUUlGbGzIPQInHYzEKB4PgjJRvImMLRKuzt0d8czZqAmUaX86ORkTLUOnatHgTNWGyMOi8E4LmMdwsFkirdMVm005bkiooOX'
        );

        $stripe->paymentIntents->create([
            'amount' => 1099,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
        ]);
        
        return view('dashboard.payments');
    }
}
