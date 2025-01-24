<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultationCreated;
use App\Models\Client;
use App\Models\Consultation;

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

        $first_name = $client->first_name;
        $email = $client->email;
        $app_name = config('app.name');

        // Send email
        Mail::to($email)->send(new ConsultationCreated($first_name, $app_name));

        // Mark Step 3 as completed (Payment made)
        $client->update(['registration_status' => 'step_3_completed']);

        return redirect()->back()->with('success', 'Payment successful');
        // return view('stripe.success');
    }
}
