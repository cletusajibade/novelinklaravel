<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

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

        //Note:  All API requests expect amount values in the currency’s minor unit. For example, enter:
        // - 1000 to charge 10 USD (or any other two-decimal currency).
        // - 10 to charge 10 JPY (or any other zero-decimal currency).
        // https://docs.stripe.com/currencies
        $total_consultation_fee = session('totalAmount') ?? 0;
        $total_consultation_fee *= 100;


        $intent = $stripe->paymentIntents->create([
            'amount' => $total_consultation_fee,
            'currency' => strtolower(session('currency')) ?? 'cad',
            'automatic_payment_methods' => ['enabled' => true], //these are all the payment methods enabled on Stripe dashboard
        ]);

        // Pass url to the view to be used by the Stripe Payment Element as return_url
        $return_url = route('stripe.success');

        return view('stripe.checkout', compact('intent', 'total_consultation_fee', 'pub_key', 'return_url'));
    }

    public function success(Request $request)
    {
        // Users should only see the success page after filling the consultaion form.
        // If not, redirect them to the consultation form.
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        try {

            $secret_key = env('STRIPE_SECRET_KEY');
            $stripe = new StripeClient($secret_key);

            // Get the request objects
            $payment_intent = $request->payment_intent;
            $redirect_status = $request->redirect_status;
            $payment_intent_client_secret = $request->payment_intent_client_secret;

            // Retrieve the payment intent using the id
            $paymentIntent = $stripe->paymentIntents->retrieve($payment_intent);

            // Get this client from the DB using the already existing client_id in session
            $client = Client::find(session('client_id'));
            if ($client) {
                // Mark Step 3 as completed (Payment made)
                $result = $client->update(['registration_status' => 'step_3/4_completed']);

                if ($result) {
                    // Insert payment into table
                    $payment = Payment::create([
                        'client_id' => $client->id,
                        'payment_id' => $paymentIntent->id,
                        'amount' => doubleval($paymentIntent->amount) / 100.0,
                        'currency' => $paymentIntent->currency,
                        'status' => $paymentIntent->status,
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

                    if ($payment) {

                        // Update pament status in Appointments table
                        $appointment = Appointment::where('client_id', $client->id)->where('status', 'pending')->first();
                        $appointment->update(['payment_status' => $paymentIntent->status]);

                        return redirect()->back()->with('success', 'Payment successful');
                    } else {
                        Log::error("Error creating a new Payment");
                    }
                } else {
                    Log::error("Error updating Client");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }
}
