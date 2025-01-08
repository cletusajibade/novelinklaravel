<?php

namespace App\Http\Controllers;

use App\Mail\SendUserEmail;
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Mail;



class ConsultationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultation::latest()->get();
        return view('dashboard.consultations', ['consultations' => $consultations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $consultation = Consultation::where('uuid', $uuid)->first();
        return view('dashboard.consultation', compact('consultation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request->user_id);
        Consultation::destroy($request->user_id);
        return redirect()->back()->with('success', 'Client deleted successfully!');
    }

    public function sendEmail(Request $request)
    {
        try {
            Mail::to($request->email)->send(new SendUserEmail($request->first_name, $request->last_name, $request->email_message, config('app.name')));
            return redirect()->route('consultations')->with('success', 'Email sent');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Email failure, please try again.']);
        }
    }
}
