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
        $payments = Payment::latest()->get();
        return view('dashboard.payments', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     * In our case, we are showing a payment form.
     */
    public function create(Request $request)
    {
        // Check if client_id is in session
        if (!session('client_id')) {
            return redirect()->route('client.create');
        }

        $client = Client::find(session('client_id'));

        // Redirect if client does not exist in database
        if (!$client) {
            return redirect()->route('client.create');
        }

        $stripePaymentId = $client->latest_stripe_payment_id;
        Log::info('Stripe Payment ID from clients table: ' . $stripePaymentId);

        // Check for existing payments and appointments
        $payment = $stripePaymentId
            ? Payment::where('stripe_payment_id', $stripePaymentId)
                ->where('status', 'succeeded')
                ->first()
            : null;

        $latestAppointment = $payment
            ? Appointment::where('payment_id', $payment->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first()
            : null;

        $latestAppointmentCompletedOrCanceled = $payment
        ? Appointment::where('payment_id', $payment->id)
            ->whereIn('status', ['completed', 'canceled'])
            ->first()
        : null;

        Log::info('Latest pending or confirmed appointment: ' . json_encode($latestAppointment));

        $previousAppointment = Appointment::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        $previousAppointmentCompletedOrCanceled = Appointment::where('client_id', $client->id)
            ->whereIn('status', ['completed', 'canceled'])
            ->first();

        Log::info('Previous pending or confirmed appointment: ' . json_encode($previousAppointment));

        $hasAppointments = Appointment::where('client_id', $client->id)->exists();
        Log::info("hasAppointments: ". $hasAppointments);

        // Handle redirect logic based on appointment and payment status
        if ($payment && !$hasAppointments) {
            Log::info("Cond 1");
            return redirect()->route('stripe.info.confirmed-payment');
        }

        if ($previousAppointment) {
            Log::info("Cond 2");
            return redirect()->route('stripe.info.pending-or-confirmed-appointment', [
                'token' => $client->unique_token,
                'appointment_uuid' => $previousAppointment->uuid,
            ]);
        }

        if ($payment && $latestAppointment) {
            Log::info("Cond 3");
            return redirect()->route('stripe.info.confirmed-payment');
        }

        if ($latestAppointment) {
            Log::info("Cond 4");
            return redirect()->route('stripe.info.pending-or-confirmed-appointment', [
                'token' => $client->unique_token,
                'appointment_uuid' => $latestAppointment->uuid,
            ]);
        }

        // if($latestAppointmentCompletedOrCanceled || $previousAppointmentCompletedOrCanceled){
        //     Log::info("Cond 5");
        //     return redirect()->route('appointment.completed');
        // }

        // Create a new Stripe payment intent if no valid appointments or payments exist
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

            //Note:  All API requests expect amount values in the currency’s minor unit. For example, enter:
            // - 1000 to charge 10 USD (or any other two-decimal currency).
            // - 10 to charge 10 JPY (or any other zero-decimal currency).
            // https://docs.stripe.com/currencies
            $totalFee = (session('totalAmount') ?? 0) * 100; // Convert to minor currency unit
            $currency = strtolower(session('currency') ?? 'cad');

            $intent = $stripe->paymentIntents->create([
                'amount' => $totalFee,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
            ], [
                'idempotency_key' => Str::uuid(),
            ]);

            Log::info('Stripe Payment Intent created successfully', ['intent_id' => $intent->id]);

            $return_url = route('stripe.success');

            return view('stripe.checkout', [
                'intent' => $intent,
                'total_consultation_fee' => $totalFee,
                'pub_key' => env('STRIPE_PUB_KEY'),
                'return_url' => $return_url,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Payment Intent creation failed: ' . $e->getMessage());
            return redirect()->route('client.create')->with('error', 'An error occurred while processing your payment.');
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
                    'confirmation_no'=> $transactionNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
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

                    $confirmation_no = $new_payment->confirmation_no;


                    // Send Payment confirmation email.
                    // Send along an appointment booking and rescheduling links with the unique token and payment_id in case they failed to book the appointment after payment stage.
                    // This enables the client to book an appointment at a later time
                    $link_book = route('appointment.create', ['token' => $client->unique_token, 'payment_uuid' => $new_payment->uuid]);
                    Log::info('$link_book: '.$link_book);
                    $links = ['booking' => $link_book];
                    Mail::to($client->email)->send(new PaymentCreated($client->first_name, config('app.name'), strtoupper($currency) . ' $' . $amount, $links, $str_packages, $confirmation_no));

                    return view('stripe.success-v2');
                } else {
                    Log::error("Error creating a new Payment");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }

    public function pendingOrConfirmedAppointment(string $unique_token=null, string $appointment_uuid=null)
    {
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        if($unique_token and $appointment_uuid){
            $client = Client::where('unique_token', $unique_token)->first();
            $first_name = $client->first_name;
            $reschedule_link = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$appointment_uuid]);
            return view('stripe.confirmed-or-pending-appointment', compact('first_name','reschedule_link'));
        }else{
            return view('stripe.confirmed-or-pending-appointment');
        }
    }

    public function confirmedPayment()
    {
        if (!session()->has('client_id')) {
            return redirect()->route('client.create');
        }

        return view('stripe.confirmed-payment');
    }
}
