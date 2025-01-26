<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultationCreated;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::latest()->get();
        return view('dashboard.appointments', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!session('client_id')) {
            return redirect()->route('client.create');
        }

        try {
            //Get this client from the DB using the already existing client_id in session
            $client = Client::find(session('client_id'));

            // Send unblocked, available or canceled time slots to the calendar view.
            // Make sure they are dates not in the past i.e 'date >= today' and time >= 3hours
            $now = Carbon::now();
            $today = Carbon::today();
            $timeThreshold = $now->addHours(1)->format('H:i:s');
            $time_slots = TimeSlot::where('start_date', '>=', $today)
                // ->where('start_time', '>=', $timeThreshold)
                ->whereBetween('start_time', ['09:00:00', '18:00:00'])
                ->where('blocked', false)
                ->whereIn('status', ['available', 'canceled'])
                ->orderBy('start_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();

            return view('appointment.calendar', compact('time_slots'));
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i:s',
            'duration' => 'nullable|integer|min:1',
            'status' => 'nullable|in:pending,confirmed,completed,canceled',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:65535',
            'reminder_at' => 'nullable|date|after_or_equal:now',
            'cancellation_reason' => 'nullable|string|max:65535',
            'payment_status' => 'nullable|string|max:255', //
        ]);

        try {
            // 1. First check that the client does not have a pending or confirmed appointment.
            $pending_or_confirmed_appointment = Appointment::where('client_id', $data['clientId'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('created_at', 'asc')
                ->first();

            Log::info($pending_or_confirmed_appointment);
            // dd($pending_or_confirmed_appointment);

            if ($pending_or_confirmed_appointment) {
                //2. Appointment already exists. The client should either reschedule or cancel.
                // Flash an error message that can be retrieved from the view
                session()->flash('error', 'You have a pending or an already confirmed appointment. You may reschedule, cancel or contact admin.');
                return response()->json(['message' => 'Error: You have a pending or an already confirmed appointment.']);
            } else {
                //3. No pending or confirmed appointment.
                // Get the selected TimeSlot from the time_slots table
                $time_slot = TimeSlot::where('start_date', $data['date'])->where('start_time', $data['time'])->first();
                if ($time_slot) {
                    //4. Time slot found; update the 'action_by' and 'status' fields
                    $result = $time_slot->update(['action_by' => $data['clientId'], 'status' => 'booked']);
                    if ($result) {
                        // 5. Time slot updated.Create an entry in appointments table also.
                        $new_appointment = Appointment::create([
                            'client_id' => $data['clientId'],
                            'appointment_date' => $data['date'],
                            'appointment_time' => $data['time'],
                            'duration' => $data['duration'],
                            'status' => 'pending',
                            'location' => 'Zoom',
                            'payment_status' => session('payment_status') ?? null
                        ]);

                        if ($new_appointment) {
                            //4. New appointment created.
                            //Get this client from the DB
                            $client = Client::find($data['clientId']);

                            $first_name = $client->first_name;
                            $email = $client->email;
                            $app_name = config('app.name');

                            // Send email
                            Mail::to($email)->send(new ConsultationCreated($first_name, $app_name));

                            // Mark final step 4 as completed (Appointment booked)
                            $client->update(['registration_status' => 'step_4/4_completed:appointment_booked']);

                            //Finally flash a success message that can be retrieved from the view
                            session()->flash('success', 'Your appointment was successfully booked! You may close this tab or window.');
                            return response()->json(['message' => 'success']);
                        } else {
                            session()->flash('error', 'Error booking your appointment, try again or contact the admin.');
                            return response()->json(['error' => 'Create issues'], 404);
                        }
                    } else {
                        session()->flash('error', 'Error booking your appointment, try again or contact the admin.');
                        return response()->json(['error' => 'Update issues'], 404);
                    }
                } else {
                    session()->flash('error', 'Error booking your appointment, try again or contact the admin.');
                    return response()->json(['error' => 'No record found'], 404);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
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
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
