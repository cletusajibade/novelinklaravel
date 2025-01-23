<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\ConsultationPackages;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = ConsultationPackages::all();
        return view('consultation.form', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        /**
         * The 'StoreClientRequest' class, type-hinted in this 'store' method declaration, contains all the validation rules.
         * It has validated the incoming form request before this 'store' controller method is called.
         * If the request is invalid, the user will be redirected back to the form with the validation errors.
         **/
        $data = $request->validated();

        // Get a sum of charges/amount of the selected package.
        $totalAmount = ConsultationPackages::whereIn('id', array_values($data['consultation_package']))->sum('amount');
        // Store this in session to be used on the checkout page.
        session(['totalAmount' => $totalAmount]);

        try {

            // Using 'updateOrCreate' function here since we are dealing with
            // clients who can return for conultations at a later date.
            $client = Client::updateOrCreate(
                ['email' => $data['email']],
                [
                    'uuid' => Str::uuid(),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'date_of_birth' => $data['date_of_birth'],
                    'country' => $data['country'],
                    'country_of_residence' => $data['country_of_residence'],
                    'consultation_package' => json_encode($data['consultation_package']),
                    'registration_status' => 'step_1_completed' // Mark Step 1 as completed
                ],
            );

            // Also insert record into Payments table
            // Payment::create([
            //     'client_id',
            //     'payment_id',
            //     'amount',
            //     'currency',
            //     'status',
            //     'stripe_customer_id',
            //     'payment_method_id',
            //     'payment_method_type',
            //     'card_brand',
            //     'card_last4',
            //     'description',
            //     'refund_status',
            //     'refund_amount',
            //     'dispute_status',
            // ]);

            // Store Client ID in session
            session(['client_id' => $client->id]);

            //Redirect to the terms of agreement page
            return redirect()->route('client.terms');
        } catch (Exception $e) {
            Log::error('Error updating or creating record: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while processing your application. Please try again or conatct us for assistance.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    public function terms()
    {
        if (!session('client_id')) {
            return redirect()->route('client.create');
        }

        $client = Client::find(session('client_id'));
        $full_name = $client->first_name . " " . $client->last_name;

        return view('terms', compact('full_name'));
    }

    public function post_terms(Request $request)
    {
        if (!session('client_id')) {
            return redirect()->route('client.create'); //->with('error', 'You cannot access this page.');;
        }

        // Create a custom Validator
        $validator = Validator::make(
            $request->all(),
            [
                'check_agree' => 'required',
                'agree_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            ],
            [
                'check_agree.required' => 'Click the checkbox above to accept this agreement.',
                'agree_name.required' => 'Enter your full name to accept this agreement.',
                'agree_name.regex' => 'The name field can only contain letters or space.',
                'agree_name.max' => 'The name field can only contain 255 characters or less.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->route('client.terms')
                ->withErrors($validator)
                ->withInput();
        }

        //Get this client from the DB using the already existing client_id in session
        $client = Client::find(session('client_id'));

        // Mark Step 2 as completed (agreement signed)
        $client->update(['registration_status' => 'step_2_completed']);

        // Redirect to the paymentpage
        return redirect()->route('stripe.create');
    }
}
