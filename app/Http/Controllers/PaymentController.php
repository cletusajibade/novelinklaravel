<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Client;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * In our case, we are creating a payment intent
     */
    public function create(Request $request)
    {
        if (!session('client_id')) {
            return redirect()->route('client.create'); //->with('error', 'You cannot access this page.');;
        }

        $pub_key = env('STRIPE_PUB_KEY');
        $secret_key = env('STRIPE_SECRET_KEY');

        $stripe = new StripeClient($secret_key);

        $total_consultation_fee = 100 * 100;

        $intent = $stripe->paymentIntents->create([
            'amount' => $total_consultation_fee,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true], //these are all the payment methods enabled on Stripe dashboard
        ]);

        // Pass url to the view to be used by the Stripe Payment Element as return_url
        $return_url = route('stripe.success');

        return view('stripe.checkout', compact('intent', 'total_consultation_fee', 'pub_key', 'return_url'));
    }

    public function success()
    {
        // Users should only see the success page after filling the consultaion form.
        // If not, redirect them to the consultation form.
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        //Get this client from the DB using the already existing client_id in session
        $client = Client::find(session('client_id'));
        // Mark Step 3 as completed (Payment made)
        $client->update(['registration_status' => 'step_3/4_completed']);

        // Update payment table
        Payment::create([
            'client_id' => $client->client_id,
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
        ]);

        return redirect()->back()->with('success', 'Payment successful');
    }
}
