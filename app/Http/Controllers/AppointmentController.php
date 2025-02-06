<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelpers;
use App\Mail\AppointmentCreated;
use App\Mail\AppointmentCanceled;
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
        // $appointments = Appointment::latest()->get();
        $appointments = Appointment::join('time_slots', 'appointments.time_slot_id', '=', 'time_slots.id')
            ->select(
                'appointments.*',
                'time_slots.start_date as appointment_date',
                'time_slots.start_time as appointment_time',
                'time_slots.duration as duration'
            )
            ->orderBy('appointments.created_at', 'desc')
            ->get();

        return view('dashboard.appointments', compact('appointments'));
    }

    /**
     * Show the calendar for creating a new resource.
     */
    public function create(string $client_token = null, string $payment_uuid = null)
    {
        Log::info('client_token: ' . $client_token);

        // Remove rescheduling flag from session
        session()->forget('isRescheduling');

        // If client_token is provided, handle email-based appointment booking
        if ($client_token) {
            $client = Client::where('unique_token', $client_token)->first();
            Log::info("client: " . $client);

            if (!$client) {
                return abort(404, 'Invalid token.');
            }

            // Store client ID in the session
            session(['client_id' => $client->id]);

            $payment = null;
            $payment_in_appointments_table = null;
            $pending_or_confirmed_appointment = null;
            $completed_or_canceled_appointment = null;

            // Handle payment UUID
            if ($payment_uuid) {
                Log::info("payment_uuid: ".$payment_uuid);
                $payment = Payment::where('uuid', $payment_uuid)
                    ->where('status', 'succeeded')
                    ->first();

                if ($payment) {
                    $payment_in_appointments_table = Appointment::where('payment_id', $payment->id)->first();

                    $pending_or_confirmed_appointment = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();

                    $completed_or_canceled_appointment = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['completed', 'canceled'])
                        ->first();

                    Log::info('payment_in_appointment_table: ' . $payment_in_appointments_table);
                    Log::info('pending_or_confirmed_appointment: ' . $pending_or_confirmed_appointment);
                    Log::info('completed_or_canceled_appointment: ' . $completed_or_canceled_appointment);
                }
            }

            // Check for previous pending or confirmed appointments for the client
            $previous_pending_or_confirmed_appointment = Appointment::where('client_id', $client->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
            Log::info('previous_pending_or_confirmed_appointment: ' . $previous_pending_or_confirmed_appointment);

            // Check for previous completed or canceled appointments for the client
            $previous_completed_or_canceled_appointment = Appointment::where('client_id', $client->id)
                ->whereIn('status', ['completed', 'canceled'])
                ->first();
            Log::info('previous_completed_or_canceled_appointment: ' . $previous_completed_or_canceled_appointment);

            // Handle appointment logic
            if ($previous_pending_or_confirmed_appointment) {
                Log::info("Appointment@create: cond 1");
                return redirect()->route('stripe.info.pending-or-confirmed-appointment');
            }

            if (isset($payment_in_appointments_table) && !$payment_in_appointments_table) {
                Log::info("Appointment@create: cond 2");
                return redirect()->route('appointment.create');
            }

            if (isset($payment_in_appointments_table, $pending_or_confirmed_appointment) && $payment_in_appointments_table && $pending_or_confirmed_appointment) {
                Log::info("Appointment@create: cond 3");
                return redirect()->route('stripe.info.pending-or-confirmed-appointment');
            }

            if(isset($completed_or_canceled_appointment) && ($completed_or_canceled_appointment || $previous_completed_or_canceled_appointment) && $payment_in_appointments_table){
                Log::info("Appointment@create: cond 4");
                return redirect()->route('appointment.completed');
            }

            // if ($payment) {
            //     Log::info("Appointment@create: cond 5");
            //     return redirect()->route('stripe.info.confirmed-payment');
            // }

            // Load and display time slots
            $time_slots = $this->loadTimeSlots();
            return view('appointment.client-calendar', compact('time_slots'));
        }
        else{

            // Handle direct route access without client_token
            if (!session('client_id')) {
                return redirect()->route('client.create');
            }

            $client = Client::find(session('client_id'));
            Log::info("client: " . $client);


            $payment = Payment::where('stripe_payment_id', $client->latest_stripe_payment_id)
                ->where('status', 'succeeded')
                ->first();

            if ($payment) {
                $payment_in_appointments_table = Appointment::where('payment_id', $payment->id)->first();

                $pending_or_confirmed_appointment = Appointment::where('payment_id', $payment->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->first();

                $completed_or_canceled_appointment = Appointment::where('payment_id', $payment->id)
                    ->whereIn('status', ['completed', 'canceled'])
                    ->first();

                Log::info('payment_in_appointment_table: ' . $payment_in_appointments_table);
                Log::info('pending_or_confirmed_appointment: ' . $pending_or_confirmed_appointment);
                Log::info('completed_or_canceled_appointment: ' . $completed_or_canceled_appointment);
            }


            // Check for previous pending or confirmed appointments for the client
            $previous_pending_or_confirmed_appointment = Appointment::where('client_id', $client->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
            Log::info('previous_pending_or_confirmed_appointment: ' . $previous_pending_or_confirmed_appointment);

            // Check for previous completed or canceled appointments for the client
            $previous_completed_or_canceled_appointment = Appointment::where('client_id', $client->id)
                ->whereIn('status', ['completed', 'canceled'])
                ->first();
            Log::info('previous_completed_or_canceled_appointment: ' . $previous_completed_or_canceled_appointment);

            // Handle appointment logic
            if ($previous_pending_or_confirmed_appointment) {
                Log::info("Appointment@create No token: cond 1");
                return redirect()->route('stripe.info.pending-or-confirmed-appointment');
            }

            if (isset($payment_in_appointments_table) && !$payment_in_appointments_table) {
                Log::info("Appointment@create No token: cond 2");
                return redirect()->route('appointment.create');
            }

            if (isset($payment_in_appointments_table, $pending_or_confirmed_appointment) && $payment_in_appointments_table && $pending_or_confirmed_appointment) {
                Log::info("Appointment@create No token: cond 3");
                return redirect()->route('stripe.info.pending-or-confirmed-appointment');
            }

            if(isset($completed_or_canceled_appointment) && ($completed_or_canceled_appointment || $previous_completed_or_canceled_appointment) && $payment_in_appointments_table){
                Log::info("Appointment@create No token: cond 4");
                return redirect()->route('appointment.completed');
            }


            // Load and display time slots
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

            $current_payment = Payment::where('stripe_payment_id', $client->latest_stripe_payment_id)->first();
            $current_payment_appointment = Appointment::where('payment_id', $current_payment->id)->exists();
            Log::info('Appointment@store current_payment: ' . $current_payment);
            Log::info('Appointment@store current_payment_appointment: ' . $current_payment_appointment);

            // check if this client has a canceled appointment
            $appoinmentCanceled = Appointment::where('client_id', $client->id)->whereIn('status',['canceled','completed'])->exists();
            Log::info('Appointment canceled check: ' . ($appoinmentCanceled ? 'true' : 'false'));
            if($appoinmentCanceled and !$current_payment_appointment){
                try{
                    $time_slot = TimeSlot::find($data['slotId']);
                    if ($time_slot) {
                        // Time slot found; update the 'client_id' and 'status' fields
                        $result = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                        if ($result) {
                            // Retrieve the latest stripe payment id of this Client from clients_table
                            $stripe_payment_id = $client->latest_stripe_payment_id;

                            Log::info('stripe_payment_id from clients table: '.$stripe_payment_id);

                            // Get payment id from payments table
                            $payment = Payment::where('stripe_payment_id', $stripe_payment_id)->where('status','succeeded')->first();
                            $payment_id = $payment->id;

                            // Create a new appointment.
                            $new_appointment = Appointment::create([
                                'uuid'=>Str::uuid(),
                                'client_id' => $data['clientId'],
                                'payment_id' => $payment_id,
                                'time_slot_id' => $time_slot->id,
                                'status' => 'confirmed',
                                'confirmation_no'=> $transactionNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
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

                                $confirmation_no = $new_appointment->confirmation_no;

                                // Send email
                                $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$new_appointment->uuid]);
                                $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$new_appointment->uuid]);
                                Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, $confirmation_no, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

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
            }else{
                session()->flash('error', 'You have a completed or canceled appointment. You may book another consultation session with us.');
                // return redirect()->route('appointment.completed');
                return response()->json(['message' => 'Your appointment has been completed or canceled!'], 404);
            }
        }
        else{
            // Client is not coming from email, most likely from from Step 3 (payment stage)
            if (!session()->has('client_id')) {
                return redirect()->route('client.create');
            }

            // Get the client using the id in session storage
            $client = Client::find(session('client_id'));

            $current_payment = Payment::where('stripe_payment_id', $client->latest_stripe_payment_id)->first();
            $current_payment_appointment = Appointment::where('payment_id', $current_payment->id)->exists();
            Log::info('Appointment@store current_payment: ' . $current_payment);
            Log::info('Appointment@store current_payment_appointment: ' . $current_payment_appointment);


            // check if this client has a canceled appointment
            $appoinmentCanceled = Appointment::where('client_id', $client->id)->whereIn('status',['canceled','completed'])->exists();
            Log::info('Appointment canceled check: ' . ($appoinmentCanceled ? 'true' : 'false'));
            if($appoinmentCanceled and !$current_payment_appointment){

                $pending_or_confirmed_appointment = Appointment::where('client_id', $data['clientId'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereNotNull('time_slot_id')
                    ->whereNotNull('payment_id')
                    ->orderBy('created_at', 'asc')
                    ->first();

                if(!$pending_or_confirmed_appointment ){
                    try{
                        $time_slot = TimeSlot::find($data['slotId']);
                        if ($time_slot) {
                            // Time slot found; update the 'client_id' and 'status' fields
                            $result = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                            if ($result) {
                                // Retrieve the latest stripe payment id of this Client from clients_table
                                $stripe_payment_id = $client->latest_stripe_payment_id;

                                Log::info('stripe_payment_id from clients table: '.$stripe_payment_id);

                                // Get payment id from payments table
                                $payment = Payment::where('stripe_payment_id', $stripe_payment_id)->where('status','succeeded')->first();
                                $payment_id = $payment->id;

                                // Create a new appointment.
                                $new_appointment = Appointment::create([
                                    'uuid'=>Str::uuid(),
                                    'client_id' => $data['clientId'],
                                    'payment_id' => $payment_id,
                                    'time_slot_id' => $time_slot->id,
                                    'status' => 'confirmed',
                                    'confirmation_no'=> $transactionNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
                                    'location' => 'Zoom',
                                ]);

                                if ($new_appointment) {

                                    $first_name = $client->first_name;
                                    $email = $client->email;

                                    // Convert time in MST to client's timezone and locale
                                    $clientTzTime = UtilHelpers::convertMstToClientTz($time_slot->start_time, $data['timezone'] ?? 'UTC', $data['locale']);

                                    // Convert the date to client's locale
                                    $clientDateLocale = UtilHelpers::formatDateToClientLocale($data['date'], $data['timezone'] ?? 'UTC', $data['locale']);

                                    $confirmation_no = $new_appointment->confirmation_no;

                                    // Send email
                                    $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$new_appointment->uuid]);
                                    $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$new_appointment->uuid]);
                                    Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, $confirmation_no, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

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
                    session()->flash('error', 'You have a pending or an already confirmed appointment. You may reschedule, cancel or contact admin.');
                    return response()->json(['error' => 'Error: You have a pending or an already confirmed appointment.'], 400);
                }
            }

            else{
                session()->flash('error', 'You have a completed or canceled appointment. You may book another consultation session with us.');
                // return redirect()->route('appointment.completed');
                return response()->json(['message' => 'Your appointment has been completed or canceled!'], 404);
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
            Log::info("appointment->status: ". $appointment->status);
            if($appointment->status == 'canceled' || $appointment->status == 'completed'){
                return redirect()->route('appointment.completed');
            }
            else{
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
                'confirmation_no'=> $transactionNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
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

                $confirmation_no = $appointment->confirmation_no;

                // Send email
                $link_reschedule = route('appointment.show-reschedule-calendar', ['appointment_uuid'=>$appointment->uuid]);
                $link_cancel = route('appointment.cancel', ['appointment_uuid'=>$appointment->uuid]);
                Mail::to($email)->send(new AppointmentCreated($first_name, $clientDateLocale, $clientTzTime, $confirmation_no, ['reschedule' => $link_reschedule, 'cancel' => $link_cancel]));

                //Finally flash a success message that can be retrieved from the view
                session()->flash('success', 'Your appointment was successfully rescheduled! Please check your email for your new meeting information. You may close this tab or window.');
                return response()->json(['message' => 'Your appointment was successfully rescheduled!'], 200);
            }

            session()->flash('error', 'Error rescheduling your appointment. Create issues (404), try again or contact the admin.');
            return response()->json(['error' => 'Failed to reschedule appointment.'], 500);
        }
    }

    public function showCancelForm(string $appointment_uuid)
    {
        // check if this client is routing to the reschedule-appointment link from their email.
        // In that case $appointment_uuid should exist
        if ($appointment_uuid) {
            $appointment = Appointment::where('uuid', $appointment_uuid)->first();
            Log::info("appointment: ". $appointment);
            $time_slot_id = $appointment->time_slot_id;
            $client_id = $appointment->client_id;

            $client = Client::find($client_id);
            $first_name = $client->first_name;
            $last_name = $client->last_name;

            $time_slot = TimeSlot::find($time_slot_id);
            $date = $time_slot->start_date;
            $time = $time_slot->start_time;

            return view('appointment.cancel', compact('client_id', 'first_name', 'last_name', 'date','time'));
        }
        else {
            return abort(404, 'No appointment found');
        }
    }

    public function cancel(Request $request, string $appointment_uuid)
    {
         $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
            'timezone' => 'nullable|string',
            'locale' => 'nullable|string',
        ]);

        Log::info($data );

        // check if this client is routing to the reschedule-appointment link from their email.
        // In that case $token should exist
        if ($appointment_uuid) {
            $appointment = Appointment::where('uuid', $appointment_uuid)->first();
            if($appointment->status != 'canceled'){
                $time_slot_id = $appointment->time_slot_id;
                $client_id = $appointment->client_id;

                $client = Client::find($client_id);
                $email = $client->email;
                $first_name = $client->first_name;
                $last_name = $client->last_name;

                $time_slot = TimeSlot::find($time_slot_id);
                $date = $time_slot->start_date;
                $time = $time_slot->start_time;

                // Process cancelation
                $appointment_updated = $appointment->update(['status'=>'canceled']);
                $time_slot_updated = $time_slot->update(['client_id'=>null, 'status'=>'available']);

                if($appointment_updated and $time_slot_updated)
                {
                    // Convert time in MST to client's timezone and locale
                    $clientTzTime = UtilHelpers::convertMstToClientTz($time, $data['timezone'] ?? 'UTC', $data['locale']);

                    // Convert the date to client's locale
                    $clientDateLocale = UtilHelpers::formatDateToClientLocale($date, $data['timezone'] ?? 'UTC', $data['locale']);

                    Mail::to($email)->send(new AppointmentCanceled($first_name, $clientDateLocale, $clientTzTime));

                    //Finally flash a success message that can be retrieved from the view
                    session()->flash('success', 'Your appointment was successfully cancelled! Please check your email for next steps or reach out to us with any questions.');
                    return response()->json(['success' => 'Appointment was successfully cancelled.'], 200);
                }
            }
            session()->flash('error', 'Your appointment is already canceled.');
            return response()->json(['error' => 'Your appointment is already canceled.'], 500);

        }
        else {
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

    public function completed()
    {
        return view('appointment.completed');
    }
}
