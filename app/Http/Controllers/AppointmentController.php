<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelpers;
use App\Mail\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Payment;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource. Used in the admin backend.
     */
    public function index()
    {
        $appointments = Appointment::latest()->get();
        return view('dashboard.appointments', compact('appointments'));
    }

    /**
     * Show the calendar for creating a new resource.
     */
    public function create(string $client_token = null, string $payment_uuid = null)
    {
        // print_r(session()->all());

        Log::info('client_token: '.$client_token);
        // Remove this variable from session storage,
        // we are crearing a new appointment not rescheduling.
        session()->forget('isRescheduling');
         Log::info(session()->all());

        // check if this client is routing to the /book-appointment route from their email.
        // In that case $client_token and $payment_uuid should exist
        if ($client_token) {
            $client = Client::where('unique_token', $client_token)->first();
            Log::info("client:". $client);
            if (!$client) {
                return abort(404, 'Invalid token.');
            }

            // Retrieve the client_id and store it in session. This is needed going forward.
            session(['client_id' => $client->id]);

            // Check if this payment exists in the appointments table
            if($payment_uuid){
                $payment= Payment::where('uuid', $payment_uuid)->where('status','succeeded')->first();
                if($payment){
                    $payment_exists = $payment;
                    $payment_id = $payment->id;

                    $payment_in_appointments_table = Appointment::where('payment_id', $payment_id)->first();
                    Log::info('payment_in_appointment_table: '. $payment_in_appointments_table);

                    $pending_or_confirmed_appointment = Appointment::where('payment_id',$payment_id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->first();
                Log::info('pending_or_confirmed_appointment: '.$pending_or_confirmed_appointment);
                }
            }

             // Check if there is any previous pending or confirmed appointment
            $previous_pending_or_confirmed_appointment = Appointment::where('client_id',$client->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();
            Log::info('previous_pending_or_confirmed_appointment: '.$previous_pending_or_confirmed_appointment);


            if($previous_pending_or_confirmed_appointment){
                return redirect()->route('stripe.error');
            }
            if(isset($payment_in_appointments_table) and !$payment_in_appointments_table){
                return redirect()->route('appointment.create');
            }
            else if (isset($payment_in_appointments_table) and ($payment_in_appointments_table and  (isset($pending_or_confirmed_appointment) and $pending_or_confirmed_appointment))) {
                return redirect()->route('stripe.error');
            }
            else if(isset($payment_exists) and $payment_exists){
                return redirect()->route('stripe.error.pending');
            }
            else {
                // Load time slots and pass them to the calendar view
                $time_slots = $this->loadTimeSlots();

                return view('appointment.client-calendar', compact('time_slots'));
            }
        } else {
            // Client token is null or Client is not coming from email.
            // They are hitting the route "/book-appointment" directly, most likely immediately after making payment.
            // So, handle this scenario appropriately.

            if (!session('client_id')) {
                return redirect()->route('client.create');
            }

            // Load time slots and pass them to the calendar view
            $time_slots = $this->loadTimeSlots();
            return view('appointment.client-calendar', compact('time_slots'));
        }
    }

    /**
     * Store a newly created resource (appointment) in storage.
     */
    public function store(Request $request, string $client_token = null, string $payment_uuid = null)
    {
        $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
            'slotId' =>'required|string',
            'date' => 'required|date|after_or_equal:today',
            'timezone' => 'nullable|string',
            'locale' => 'nullable|string',
        ]);

        Log::info($data);
        Log::info("client_token: ".$client_token);

        if ($client_token) {
            // Client is coming from email, use the token to retrieve the client.
            $client = Client::where('unique_token', $client_token)->first();
            if (!$client) {
                return abort(404, 'Invalid token.');
            }

            try{
                $time_slot = TimeSlot::find($data['slotId']);
                if ($time_slot) {
                    // Time slot found; update the 'client_id' and 'status' fields
                    $result = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                    if ($result) {
                        // Retrieve the latest stripe payment id of this Client from clients_table
                        $stripe_payment_id = $client->pluck('latest_stripe_payment_id');

                        Log::info('stripe_payment_id from clients table: '.$stripe_payment_id);

                        // Get payment id from payments table
                        $payment = Payment::where('stripe_payment_id', $stripe_payment_id[0])->where('status','succeeded')->first();
                        $payment_id = $payment->id;

                        // Create a new appointment.
                        $new_appointment = Appointment::create([
                            'uuid'=>Str::uuid(),
                            'client_id' => $data['clientId'],
                            'payment_id' => $payment_id,
                            'time_slot_id' => $time_slot->id,
                            'status' => 'confirmed',
                            'location' => 'Zoom',
                        ]);

                        if ($new_appointment) {
                            // New appointment created.

                            $first_name = $client->first_name;
                            $email = $client->email;

                            // Convert time in MST to client's timezone and locale
                            $clientTzTime = UtilHelpers::convertMstToClientTz($time_slot->start_time, $data['timezone'] ?? 'UTC', $data['locale']);

                            // Convert the date to client's locale
                            $clientDateLocale = UtilHelpers::formatDateToClientLocale($data['date'], $data['timezone'] ?? 'UTC', $data['locale']);

                            // Send email
                            $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$new_appointment->uuid]);
                            $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$new_appointment->uuid]);
                            Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

                            // Mark final step 4 as completed (Appointment booked)
                            $client->update(['registration_status' => 'step_4/4_completed:appointment_booked']);

                            //Finally flash a success message that can be retrieved from the view
                            session()->flash('success', 'Your appointment was successfully booked! Please check your email for the meeting information. You may close this tab or window.');
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
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }
        }
        else{
            // Client is not coming from email, most likely from from Step 3 (payment stage)
            if (!session()->has('client_id')) {
                return redirect()->route('client.create');
            }

            // Get the client using the id in session storage
            $client = Client::find(session('client_id'));

            try{
                $time_slot = TimeSlot::find($data['slotId']);
                if ($time_slot) {
                    // Time slot found; update the 'client_id' and 'status' fields
                    $result = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                    if ($result) {
                        // Retrieve the latest stripe payment id of this Client from clients_table
                        $stripe_payment_id = $client->pluck('latest_stripe_payment_id');

                        Log::info('stripe_payment_id from clients table: '.$stripe_payment_id);

                        // Get payment id from payments table
                        $payment = Payment::where('stripe_payment_id', $stripe_payment_id[0])->where('status','succeeded')->first();
                        $payment_id = $payment->id;

                        // Create a new appointment.
                        $new_appointment = Appointment::create([
                            'uuid'=>Str::uuid(),
                            'client_id' => $data['clientId'],
                            'payment_id' => $payment_id,
                            'time_slot_id' => $time_slot->id,
                            'status' => 'confirmed',
                            'location' => 'Zoom',
                        ]);

                        if ($new_appointment) {

                            $first_name = $client->first_name;
                            $email = $client->email;

                            // Convert time in MST to client's timezone and locale
                            $clientTzTime = UtilHelpers::convertMstToClientTz($time_slot->start_time, $data['timezone'] ?? 'UTC', $data['locale']);

                            // Convert the date to client's locale
                            $clientDateLocale = UtilHelpers::formatDateToClientLocale($data['date'], $data['timezone'] ?? 'UTC', $data['locale']);

                            // Send email
                            $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$new_appointment->uuid]);
                            $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$new_appointment->uuid]);
                            Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

                            // Mark final step 4 as completed (Appointment booked)
                            $client->update(['registration_status' => 'step_4/4_completed:appointment_booked']);

                            //Finally flash a success message that can be retrieved from the view
                            session()->flash('success', 'Your appointment was successfully booked! Please check your email for the meeting information. You may close this tab or window.');
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
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }
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
    public function showRescheduleCalendar(string $appointment_uuid = null)
    {
        // print_r(session()->all());
        // Set a rescheduling flag used in the client-calendar view.
        session(['isRescheduling' => true]);

        // check if this client is routing to the reschedule-appointment link from their email.
        // In that case $appointment_uuid should exist
        if ($appointment_uuid) {
            $appointment = Appointment::where('uuid', $appointment_uuid)->first();
            if (!$appointment) {
                return redirect()->route('home');
            }

            // Store the client_id in session. This is needed going forward.
            session(['client_id' => $appointment->client_id]);

            // Get current appointment
            $time_slot = TimeSlot::find($appointment->time_slot_id);
            $current_appointment = ['start_time'=>$time_slot->start_time, 'start_date'=>$time_slot->start_date];

            // Load time slots and pass them to the calendar view
            $time_slots = $this->loadTimeSlots();

            return view('appointment.client-calendar', compact('time_slots', 'current_appointment'));
        }
    }

    public function reschedule(Request $request, string $appointment_uuid = null)
    {
          $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
            'slotId' =>'required|string',
            'date' => 'required|date|after_or_equal:today',
            'timezone' => 'nullable|string',
            'locale' => 'nullable|string',
        ]);

        if ($appointment_uuid) {
            $appointment = Appointment::where('uuid', $appointment_uuid)->first();
            $old_time_slot_id = $appointment->time_slot_id;
            $new_time_slot_id = $data['slotId'];

            $updated_appointment = $appointment->update([
                'time_slot_id'=>$new_time_slot_id,
            ]);

            if ($updated_appointment) {
                // Get the Old TimeSlot and update it
                $old_time_slot = TimeSlot::find($old_time_slot_id);
                $updated_old_time_slot = $old_time_slot->update([
                    'client_id'=>null,
                    'status'=>'available'
                ]);

                // Get the new TimeSlot and update it
                $new_time_slot = TimeSlot::find($new_time_slot_id);
                $updated_new_time_slot = $new_time_slot->update([
                    'client_id'=>$data['clientId'],
                    'status'=>'booked'
                ]);

                //Get this client from the DB
                $client = Client::find($data['clientId']);

                $first_name = $client->first_name;
                $email = $client->email;

                // Convert time in MST to client's timezone and locale
                $clientTzTime = UtilHelpers::convertMstToClientTz($new_time_slot->start_time, $data['timezone'] ?? 'UTC', $data['locale']);

                // Convert the date to client's locale
                $clientDateLocale = UtilHelpers::formatDateToClientLocale($data['date'], $data['timezone'] ?? 'UTC', $data['locale']);

                // Send email
                $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$appointment->uuid]);
                $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$appointment->uuid]);
                Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

                //Finally flash a success message that can be retrieved from the view
                session()->flash('success', 'Your appointment was successfully rescheduled! Please check your email for your new meeting information. You may close this tab or window.');
                return response()->json(['message' => 'Your appointment was successfully rescheduled!'], 200);
            }

            session()->flash('error', 'Error rescheduling your appointment. Create issues (404), try again or contact the admin.');
            return response()->json(['error' => 'Failed to reschedule appointment.'], 500);
        }
    }

    public function cancel(string $token = null, string $appointment_id = null, string $payment_id = null)
    {
        // check if this client is routing to the reschedule-appointment link from their email.
        // In that case $token should exist
        if ($token and $payment_id) {
            $client = Client::where('unique_token', $token)->first();
            $payment = Payment::where('payment_id', $payment_id)->first();

            if (!$client) {
                return abort(404, 'Invalid token.');
            }
            if (!$payment) {
                return abort(404, 'Invalid link.');
            }

            // set a rescheduling flag
            session(['isCanceling' => true]);

            // Store the client_id in session. This is needed going forward.
            session(['client_id' => $client->id]);

            // Load time slots and pass them to the calendar view
            $time_slots = $this->loadTimeSlots();

            return view('appointment.client-calendar', compact('time_slots'));
        } else {
            // Token is null or Client is not coming from email.
            // They are hitting the route "/reschedule-appointment" directly.

            return abort(404, 'No appointment found');
        }
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
            // Make sure they are dates not in the past i.e 'date >= today'
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
}
