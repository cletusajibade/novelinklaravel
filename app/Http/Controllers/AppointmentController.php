<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelpers;
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
     * Display a listing of the resource. Used in the admin backend
     */
    public function index()
    {
        $appointments = Appointment::latest()->get();
        return view('dashboard.appointments', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($token = null)
    {
        // check if this client is coming from the appointment link in the email.
        // In that case $token should exist
        if ($token) {
            $appointment = Appointment::where('unique_token', $token)->first();
            if (!$appointment) {
                return abort(404, 'Invalid token.');
            }

            // Retrieve the client_id and store it in session. This is needed going forward.
            session(['client_id' => $appointment->client_id]);

            // Load time slots and pass them to the view
            $time_slots = $this->loadTimeSlots();
            return view('appointment.calendar', compact('time_slots'));
        } else {
            // Client is not coming from email
            // They are hitting the route "/book-appointment" directly, most likely immediately after making payment.
            // So, handle this scenario appropriately.

            if (!session('client_id')) {
                return redirect()->route('client.create');
            }

            $time_slots = $this->loadTimeSlots();
            return view('appointment.calendar', compact('time_slots'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $token = null)
    {
        $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i:s',
            'duration' => 'nullable|integer|min:1',
            'timezone' => 'nullable|string',
            'locale' => 'nullable|string',
            'status' => 'nullable|in:pending,confirmed,completed,canceled',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:65535',
            'reminder_at' => 'nullable|date|after_or_equal:now',
            'cancellation_reason' => 'nullable|string|max:65535',
            'payment_status' => 'nullable|string|max:255', //
        ]);

        // Log::info(($data));
        // dd($data);

        // check if this client is coming from the appointment link in the email.
        // In that case $token should exist
        if ($token) {
            $appointment = Appointment::where('unique_token', $token)->first();
            if (!$appointment) {
                return abort(404, 'Invalid token.');
            }

            return $this->processAppointment($data);
        }

        return $this->processAppointment($data);
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

    private function loadTimeSlots()
    {
        try {
            // Send unblocked, available or canceled time slots to the calendar view.
            // Make sure they are dates not in the past i.e 'date >= today' and time >= 3hours
            $now = Carbon::now();
            $today = Carbon::today();
            $timeThreshold = $now->addHours(0)->format('H:i:s');
            $time_slots = TimeSlot::where('start_date', '>=', $today)
                // ->where('start_time', '>=', $timeThreshold)
                ->whereBetween('start_time', ['09:00:00', '18:00:00'])
                ->where('blocked', false)
                ->whereIn('status', ['available', 'canceled'])
                ->orderBy('start_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();

            return $time_slots;
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            return collect(); // Return an empty collection in case of error
        }
    }

    private function processAppointment(array $data)
    {


        try {
            // 1. Since token exists, client_id already exists and was passed from the client,
            // so we can use it to fetch the placeholder appointment created in PaymentController
            $placeholder_appointment = Appointment::where('client_id', $data['clientId'])
                ->whereIn('status', ['pending'])
                ->whereNull('appointment_date')
                ->whereNull('appointment_time')
                ->orderBy('created_at', 'asc')
                ->first();

            Log::info('placeholder_appointment: ' . $placeholder_appointment);

            if ($placeholder_appointment) {
                //3. Pending appointment.
                // Get the selected TimeSlot from the time_slots table
                $time_slot = TimeSlot::where('start_date', $data['date'])->where('start_time', $data['time'])->first();
                if ($time_slot) {
                    //4. Time slot found; update the 'action_by' and 'status' fields
                    $result = $time_slot->update(['action_by' => $data['clientId'], 'status' => 'booked']);
                    if ($result) {
                        // 5. Time slot updated. Update appointments table also.

                        // First check the payments table, get the payment_id and use that to

                        $updated_appointment = $placeholder_appointment->update([
                            'appointment_date' => $data['date'],
                            'appointment_time' => $data['time'],
                            'duration' => $data['duration'],
                            'location' => 'Zoom',
                        ]);

                        if ($updated_appointment) {
                            //4. New appointment updated.
                            //Get this client from the DB
                            $client = Client::find($data['clientId']);

                            $first_name = $client->first_name;
                            $email = $client->email;
                            $app_name = config('app.name');

                            // Convert time in MST to client's timezone and locale
                            $clientTzTime = UtilHelpers::convertMstToClientTz($data['time'], $data['timezone'] ?? 'UTC', $data['locale']);

                            // Send email
                            Mail::to($email)->send(new ConsultationCreated($first_name, $data['date'], $clientTzTime));

                            // Mark final step 4 as completed (Appointment booked)
                            $client->update(['registration_status' => 'step_4/4_completed:appointment_booked']);

                            //Finally flash a success message that can be retrieved from the view
                            session()->flash('success', 'Your appointment was successfully booked! You may close this tab or window.');
                            return response()->json(['message' => 'Your appointment was successfully booked!'], 200);
                        }
                        session()->flash('error', 'Error booking your appointment. Create issues (404), try again or contact the admin.');
                        return response()->json(['error' => 'Failed to update appointment.'], 500);
                    }
                    session()->flash('error', 'Error booking your appointment. Update issues (404), try again or contact the admin.');
                    return response()->json(['error' => 'Update issues'], 404);
                }
                session()->flash('error', 'Error booking your appointment. No record found (404), try again or contact the admin.');
                return response()->json(['error' => 'Time slot not available.'], 404);
            }
            // Appointment time has been booked and is either pending or confirmed. The client should either reschedule or cancel.
            // Also flash an error message that can be retrieved from the view.
            $pending_or_confirmed_appointment = Appointment::where('client_id', $data['clientId'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereNotNull('appointment_date')
                ->whereNotNull('appointment_time')
                ->orderBy('created_at', 'asc')
                ->first();

            Log::info('pending_or_confirmed_appointment: ' . $pending_or_confirmed_appointment);

            if ($pending_or_confirmed_appointment) {
                session()->flash('error', 'You have a pending or an already confirmed appointment. You may reschedule, cancel or contact admin.');
                return response()->json(['error' => 'Error: You have a pending or an already confirmed appointment.'], 400);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
