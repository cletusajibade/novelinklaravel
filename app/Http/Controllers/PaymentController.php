<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentCreated;
use App\Models\Appointment;
use App\Models\ConsultationPackages;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.payments');
    }

    /**
     * Show the form for creating a new resource.
     * In our case, we are showing a payment form.
     */
    public function create(Request $request)
    {
        // print_r(session()->all());

        // Is the client id still in session?
        if (!session('client_id')) {
            return redirect()->route('client.create');
        }

        // If for some reason this client id in session no longer exists in the DB,
        // redirect to create a new consulation.
        $client = Client::find(session('client_id'));
        if (!$client) {
            return redirect()->route('client.create');
        }

        // Retrieve the latest stripe payment id of this Client from clients_table
        $stripe_payment_id = $client->pluck('latest_stripe_payment_id');

        Log::info('stripe_payment_id from clients table: '.$stripe_payment_id);

        // Check if this payment exists in the appointments table
        if($stripe_payment_id){
            $payment = Payment::where('stripe_payment_id', $stripe_payment_id[0])->where('status','succeeded')->first();
            if($payment){
                $payment_exists = $payment;
                $payment_id = $payment->id;
                $latest_payment_in_appointments_table = Appointment::where('payment_id', $payment_id)->first();
                Log::info('latest_payment_in_appointments_table: '.$latest_payment_in_appointments_table);

                $latest_pending_or_confirmed_appointment = Appointment::where('payment_id',$payment_id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->first();
                Log::info('latest_pending_or_confirmed_appointment: '.$latest_pending_or_confirmed_appointment);
            }
        }

        // Check if there is any previous pending or confirmed appointment
        $previous_pending_or_confirmed_appointment = Appointment::where('client_id',$client->id)
        ->whereIn('status', ['pending', 'confirmed'])
        ->first();
        Log::info('previous_pending_or_confirmed_appointment: '.$previous_pending_or_confirmed_appointment);


        if ($previous_pending_or_confirmed_appointment){
            return redirect()->route('stripe.error');
        }
        else if (isset($latest_payment_in_appointments_table) and !$latest_payment_in_appointments_table) {
            return redirect()->route('stripe.error.pending');
        }
        else if(isset($latest_payment_in_appointments_table) and ($latest_payment_in_appointments_table and (isset($latest_pending_or_confirmed_appointment) and $latest_pending_or_confirmed_appointment))){
            session(['token' => $client->unique_token]);
            return redirect()->route('stripe.error');
        }else if(isset($payment_exists) and $payment_exists){
            return redirect()->route('stripe.error.pending');
        }
         else {
            try {
                $pub_key = env('STRIPE_PUB_KEY');
                $secret_key = env('STRIPE_SECRET_KEY');

                $stripe = new StripeClient($secret_key);

                //Note:  All API requests expect amount values in the currency’s minor unit. For example, enter:
                // - 1000 to charge 10 USD (or any other two-decimal currency).
                // - 10 to charge 10 JPY (or any other zero-decimal currency).
                // https://docs.stripe.com/currencies
                $total_consultation_fee = session('totalAmount') ?? 0;
                $total_consultation_fee *= 100;

                $intent = $stripe->paymentIntents->create(
                    [
                        'amount' => $total_consultation_fee,
                        'currency' => strtolower(session('currency')) ?? 'cad',
                        'automatic_payment_methods' => ['enabled' => true], //these are all the payment methods enabled on Stripe dashboard
                    ],
                    [
                        'idempotency_key' => Str::uuid(), // to prevent multiple payments
                    ]
                );

                // Pass url to the view to be used by the Stripe Payment Element as return_url
                $return_url = route('stripe.success');

                return view('stripe.checkout', compact('intent', 'total_consultation_fee', 'pub_key', 'return_url'));
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
            }
        }
    }

    public function success(Request $request)
    {
        // Users should only see the success page after steps 1 - 3 have been completed.
        // If not, redirect them to the consultation form.
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        try {
            $secret_key = env('STRIPE_SECRET_KEY');
            $stripe = new StripeClient($secret_key);

            // Get the stripe payment request objects
            $payment_intent = $request->payment_intent;
            $redirect_status = $request->redirect_status;
            $payment_intent_client_secret = $request->payment_intent_client_secret;

            // Retrieve the payment intent using the id
            $paymentIntent = $stripe->paymentIntents->retrieve($payment_intent);

            // Get this client from the DB using the already existing client_id in session
            $client = Client::find(session('client_id'));
            if ($client) {
                // Mark Step 3 as completed (Payment made), and update stripe payment id and date
                $client->update([
                    'registration_status' => 'step_3/4_completed:payment_made',
                    'latest_stripe_payment_id'=>$paymentIntent->id,
                    'latest_payment_date'=>date('Y-m-d H:i:s',$paymentIntent->created)
                ]);

                // Insert payment info into table
                $amount = doubleval($paymentIntent->amount) / 100.0;
                $currency = $paymentIntent->currency;
                $new_payment = Payment::create([
                    'uuid'=>Str::uuid(),
                    'client_id' => $client->id,
                    'stripe_payment_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'currency' => strtoupper($currency),
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

                if ($new_payment) {
                    // Store payment id in session to be used in Appointment controller
                    session(['payment_id' => $new_payment->id]);

                    // Convert the consultation packages JSON string to array
                    $array_packages = json_decode($client->consultation_package);

                    // Use the values of the array to fetch the actual package names from the ConsultationPackages model table.
                    $packages = ConsultationPackages::whereIn('id', $array_packages)->pluck('package_name');
                    $str_packages = implode(", ", $packages->toArray());

                    // Send Payment confirmation email.
                    // Send along an appointment booking and rescheduling links with the unique token and payment_id in case they failed to book the appointment after payment stage.
                    // This enables the client to book an appointment at a later time
                    $link_book = route('appointment.create', ['token' => $client->unique_token, 'payment_uuid' => $new_payment->uuid]);
                    Log::info('$link_book: '.$link_book);
                    $links = ['booking' => $link_book];
                    Mail::to($client->email)->send(new PaymentCreated($client->first_name, config('app.name'), strtoupper($currency) . ' $' . $amount, $links, $str_packages));

                    return view('stripe.success-v2');
                } else {
                    Log::error("Error creating a new Payment");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }

    public function error()
    {
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        return view('stripe.error');
    }

    public function pending()
    {
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        return view('stripe.pending');
    }
}
