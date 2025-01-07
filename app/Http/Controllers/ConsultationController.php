<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultationCreated;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    /**
     * Show the form for creating a new consultation.
     */
    public function create()
    {
        // TODO: Troubleshoot why countries in dropdown not scrollable.
        // $filePath = resource_path('data/countries.json');
        // if (!file_exists($filePath)) {
        //     abort(404, 'Countries file not found');
        // }
        // $countries = File::json($filePath);
        // return view('book-consultation', ['countries' => $countries]);

        return view('book-consultation');
    }

    /**
     * Store a newly created consultation in storage.
     */
    public function store(StoreConsultationRequest $request)
    {
        /**
         * The 'StoreConsultationRequest' class, type-hinted in the 'store' method declaration, contains all the validation rules.
         * It has validated the incoming form request before the 'store' controller method is called.
         * If the request is invalid, the user will be redirected back to the form with the validation errors.
         **/
        $data = $request->validated();

        $consultation = new Consultation();
        $consultation->uuid = Str::uuid();
        $consultation->first_name = $data['first_name'];
        $consultation->last_name = $data['last_name'];
        $consultation->email = $data['email'];
        $consultation->phone = $data['phone'];
        $consultation->date_of_birth = $data['date_of_birth'];
        $consultation->country = $data['country'];
        $consultation->country_of_residence = $data['country_of_residence'];

        //save the participant to the database and redirect to the form with a success message, or redirect back with an error message
        try {
            if ($consultation->save()) {
                //Send Email
                Mail::to($consultation->email)->send(new ConsultationCreated($consultation->first_name, config('app.name')));
                return redirect()->route('consultation.create')->with('success', 'We have received your application. Kindly check your email soon for next steps.');
            } else {
                redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while processing your application. Please try again or conatct us for assistance.']);
            }
        } catch (Exception $e) {
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
}
