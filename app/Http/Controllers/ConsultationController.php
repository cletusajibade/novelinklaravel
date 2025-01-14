<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use App\Models\ConsultationPackages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    /**
     * Show the form for creating a new consultation.
     */
    public function create()
    {
        $packages = ConsultationPackages::all();
        return view('consultation.form', compact('packages'));
    }

    /**
     * Store a newly created consultation in storage.
     */
    public function store(StoreConsultationRequest $request)
    {
        /**
         * The 'StoreConsultationRequest' class, type-hinted in this 'store' method declaration, contains all the validation rules.
         * It has validated the incoming form request before this 'store' controller method is called.
         * If the request is invalid, the user will be redirected back to the form with the validation errors.
         **/
        $data = $request->validated();

        try {

            // Using 'updateOrCreate' function here since we are dealing with
            // clients who can return for conultations at a later date.
            $consultation = Consultation::updateOrCreate(
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

            // Store Client ID in session
            session(['client_id' => $consultation->id]);

            //Redirect to the terms of agreement page
            return redirect()->route('consultation.terms');
        } catch (Exception $e) {
            Log::error('Error updating or creating record: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while processing your application. Please try again or conatct us for assistance.']);
        }
    }

    /**
     * Display the specified consultation.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified consultation.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified consultation in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified consultation from storage.
     */
    public function destroy(string $uuid)
    {
        //
    }

    public function terms()
    {
        if (!session('client_id')) {
            return redirect()->route('consultation.create');
        }

        $consultation = Consultation::find(session('client_id'));
        $full_name = $consultation->first_name . " " . $consultation->last_name;

        return view('terms', compact('full_name'));
    }

    public function post_terms(Request $request)
    {
        if (!session('client_id')) {
            return redirect()->route('consultation.create'); //->with('error', 'You cannot access this page.');;
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
                ->route('consultation.terms')
                ->withErrors($validator)
                ->withInput();
        }

        //Get this client from the DB using the already existing client_id in session
        $consultation = Consultation::find(session('client_id'));

        // Mark Step 2 as completed (agreement signed)
        $consultation->update(['registration_status' => 'step_2_completed']);

        // Redirect to the Stripe payment page
        return redirect()->route('stripe.create');
    }
}
