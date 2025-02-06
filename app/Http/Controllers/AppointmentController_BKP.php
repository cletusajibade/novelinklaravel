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

            // *********************************************************************
            // **** Begin Appointment Calendar View Conditions: Token Available ****
            // *********************************************************************
            // Step 1. Get the client's latest successful payment from payments table
            if ($payment_uuid) {
                Log::info("payment_uuid: ".$payment_uuid);
                $payment = Payment::where('uuid', $payment_uuid)
                    ->where('status', 'succeeded')
                    ->first();

                // 1.1 If Payment exists goto-> Step 2
                if ($payment) {
                    // Step 2. Check if that payment has an appointment
                    $payment_has_appointment = Appointment::where('payment_id', $payment->id)->exists();
                    if($payment_has_appointment){
                        // 2.1 - If completed or canceled appointment goto-> create client form
                        $appointment_is_completed_or_canceled = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['completed', 'canceled'])
                        ->first();
                        if($appointment_is_completed_or_canceled){
                            return redirect()->route('client.create');
                        }

                        // 2.2 - If pending or confirmed appointment goto-> show appointment-pending message
                        $pending_or_confirmed_appointment = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();
                        if($pending_or_confirmed_appointment){
                            return redirect()->route('stripe.info.pending-or-confirmed-appointment');
                        }

                        // 2.3 - If no appointment goto-> Step 3
                    }

                }
                else{
                    // 1.2 If no payment exists goto-> create client form
                    return redirect()->route('client.create');
                }

                // Step 3 - Show calendar to book appointment
                $time_slots = $this->loadTimeSlots();
                return view('appointment.client-calendar', compact('time_slots'));

                // ********************************************************************
                // ***************** End Appointment Calendar View Conditions *********
                // ********************************************************************
            }
        }
        else{
            // Handle direct route access without client_token
            if (!session('client_id')) {
                return redirect()->route('client.create');
            }

            $client = Client::find(session('client_id'));
            Log::info("client: " . $client);

            if (!$client) {
                return abort(404, 'Invalid token.');
            }

            // ********************************************************************
            // ****** Begin Appointment Calendar View Conditions: No Token ********
            // ********************************************************************
            // Step 1. Get the client's latest successful payment from payments table
            Log::info("Step 1");
            $latestPayment = Payment::where('client_id', $client->id)
                ->where('status', 'succeeded')
                ->orderBy('created_at', 'desc')
                ->first();

            // 1.1 If Payment exists goto-> Step 2
            Log::info("Step 1.1");
            if ($latestPayment) {
                // Step 2. Check if that payment has an appointment
                Log::info("Step 2");
                $payment_has_appointment = Appointment::where('payment_id', $latestPayment->id)->exists();
                if($payment_has_appointment){
                    // 2.1 - If completed or canceled appointment goto-> create client form
                    $appointment_is_completed_or_canceled = Appointment::where('payment_id', $latestPayment->id)
                        ->whereIn('status', ['completed', 'canceled'])
                        ->first();
                    if($appointment_is_completed_or_canceled){
                        Log::info("Step 2.1");
                        return redirect()->route('client.create');
                    }

                    // 2.2 - If pending or confirmed appointment goto-> show appointment-pending message
                    $pending_or_confirmed_appointment = Appointment::where('payment_id', $latestPayment->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();
                    if($pending_or_confirmed_appointment){
                        Log::info("Step 2.2");
                        return redirect()->route('stripe.info.pending-or-confirmed-appointment');
                    }
                }
                else{
                    // 2.3 - No appointment, program logic jumps to Step 3 below.
                    Log::info("Step 2.3");
                }
            }
            else{
                // 1.2 - No payment exists goto-> create client form
                Log::info("Step 1.2");
                return redirect()->route('client.create');
            }

            // Step 3 - Show calendar to book appointment
            Log::info("Step 3");
            $time_slots = $this->loadTimeSlots();
            return view('appointment.client-calendar', compact('time_slots'));

            // ********************************************************************
            // ***************** End Appointment Calendar View Conditions *********
            // ********************************************************************
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

        if ($client_token) {
            // Client is coming from email, use the token to retrieve the client.
            $client = Client::where('unique_token', $client_token)->first();
            if (!$client) {
                return abort(404, 'Invalid token.');
            }

            // *********************************************************************
            // **** Begin Appointment Calendar View Conditions: Token Available ****
            // *********************************************************************
            // Step 1. Get the client's latest successful payment from payments table
            Log::info("Token: Step 1");
            if ($payment_uuid) {
                Log::info("payment_uuid: ".$payment_uuid);
                $payment = Payment::where('uuid', $payment_uuid)
                    ->where('status', 'succeeded')
                    ->first();

                // 1.2 If Payment exists goto-> Step 2
                Log::info("Token: Step 1.2");
                if ($payment) {
                    // Step 2. Check if that payment has an appointment
                    Log::info("Token: Step 2");
                    $payment_has_appointment = Appointment::where('payment_id', $payment->id)->exists();
                    if($payment_has_appointment){
                        // 2.1 - If completed or canceled appointment goto-> appointment.completed view
                        $appointment_is_completed_or_canceled = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['completed', 'canceled'])
                        ->first();
                        if($appointment_is_completed_or_canceled){
                            Log::info("Token: Step 2.1");
                            return redirect()->route('appointment.completed');
                        }

                        // 2.2 - If pending or confirmed appointment goto-> show appointment-pending message
                        $pending_or_confirmed_appointment = Appointment::where('payment_id', $payment->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();
                        if($pending_or_confirmed_appointment){
                            Log::info("Token: Step 2.2");
                            return redirect()->route('stripe.info.pending-or-confirmed-appointment');
                        }
                    }else{
                        // 2.3 - If no appointment goto-> Step 3
                        Log::info("Token: Step 2.3");
                    }
                }
                else{
                    // 1.2 If no payment exists goto-> create client form
                    return redirect()->route('client.create');
                }
            }
            else{
                // 1.1 No payment uuid goto-> create client
                Log::info("Token: Step 1.1");
                return redirect()->route('client.create');
            }

            // Step 3 - Book Appointment:
            try{
                $time_slot = TimeSlot::find($data['slotId']);
                if ($time_slot) {
                    // Time slot found; update the 'client_id' and 'status' fields
                    $time_slot_updated = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                    if ($time_slot_updated) {
                        // time_slot updated, update appointment also
                        if($payment_uuid){
                            // First get the succeeded payment
                            $payment = Payment::where('uuid', $payment_uuid)
                                ->where('status','succeeded')
                                ->first();
                            if($payment){
                                // Create a new appointment.
                                $new_appointment = Appointment::create([
                                    'uuid'=>Str::uuid(),
                                    'client_id' => $data['clientId'],
                                    'payment_id' => $payment->id,
                                    'time_slot_id' => $time_slot->id,
                                    'status' => 'confirmed',
                                    'confirmation_no'=> $transactionNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT),
                                    'location' => 'Zoom',
                                ]);

                                if ($new_appointment) {
                                    // New appointment created, now send email and flash a success message to client

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
                            }
                        }
                        session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                        return response()->json(['error' => 'Failed to update appointment.'], 500);
                    }
                    session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                    return response()->json(['error' => 'Update issues'], 404);
                }
                session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                return response()->json(['error' => 'Time slot not available.'], 404);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }

        }
        else{
            // Process non-email appointment booking
            if (!session()->has('client_id')) {
                return redirect()->route('client.create');
            }

            // Get the client using the id in session storage
            $client = Client::find(session('client_id'));

            // ********************************************************************
            // ********** Begin Appointment Booking Conditions: No Token **********
            // ********************************************************************
            // Step 1. Get the client's latest successful payment from payments table
            Log::info("Step 1");
            $latestPayment = Payment::where('client_id', $client->id)
                ->where('status', 'succeeded')
                ->orderBy('created_at', 'desc')
                ->first();

            // 1.1 If Payment exists goto-> Step 2
            Log::info("Step 1.1");
            if ($latestPayment) {
                // Step 2. Check if that payment has an appointment
                Log::info("Step 2");
                $payment_has_appointment = Appointment::where('payment_id', $latestPayment->id)->exists();
                if($payment_has_appointment){
                    // 2.1 - If completed or canceled appointment goto-> appointment.completed view
                    $appointment_is_completed_or_canceled = Appointment::where('payment_id', $latestPayment->id)
                        ->whereIn('status', ['completed', 'canceled'])
                        ->first();
                    if($appointment_is_completed_or_canceled){
                        Log::info("Step 2.1");
                        return redirect()->route('appointment.completed');
                    }

                    // 2.2 - If pending or confirmed appointment goto-> show appointment-pending message
                    $pending_or_confirmed_appointment = Appointment::where('payment_id', $latestPayment->id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();
                    if($pending_or_confirmed_appointment){
                        Log::info("Step 2.2");
                        return redirect()->route('stripe.info.pending-or-confirmed-appointment');
                    }
                }
                else{
                    // 2.3 - No appointment, program logic jumps to Step 3 below.
                    Log::info("Step 2.3");
                }
            }
            else{
                // 1.2 - No payment exists goto-> create client form
                Log::info("Step 1.2");
                return redirect()->route('client.create');
            }

            // Step 3 - Book appointment:
            try{
                $time_slot = TimeSlot::find($data['slotId']);
                if ($time_slot) {
                    // Time slot found; update the 'client_id' and 'status' fields
                    $time_slot_updated = $time_slot->update(['client_id' => $data['clientId'], 'status' => 'booked']);
                    if ($time_slot_updated) {

                         $latestPayment = Payment::where('client_id', $client->id)
                            ->where('status', 'succeeded')
                            ->orderBy('created_at', 'desc')
                            ->first();

                        if($latestPayment){
                            // Create a new appointment.
                            $new_appointment = Appointment::create([
                                'uuid'=>Str::uuid(),
                                'client_id' => $data['clientId'],
                                'payment_id' => $latestPayment->id,
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
                                // session()->flash('success', 'Your appointment was successfully booked! Please check your email for the meeting information. You may close this tab or window.');
                                return response()->json(['message' => 'Your appointment was successfully booked! Please check your email for the meeting information. You may close this tab or window.'], 200);
                            }
                            // session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                            return response()->json(['error' => 'Error booking your appointment. Try again or contact the admin.'], 500);
                        }
                        // session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                        return response()->json(['error' => 'Error booking your appointment. Try again or contact the admin.'], 404);
                    }
                    // session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                    return response()->json(['error' => 'Error booking your appointment. Try again or contact the admin.'], 404);
                }
                // session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                return response()->json(['error' => 'Error booking your appointment. Try again or contact the admin.'], 404);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                // session()->flash('error', 'Error booking your appointment. Try again or contact the admin.');
                return response()->json(['error' => 'Error booking your appointment. Try again or contact the admin.'], 500);
            }
            // ********************************************************************
            // *********** End Appointment Booking Conditions: No Token ***********
            // ********************************************************************
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
