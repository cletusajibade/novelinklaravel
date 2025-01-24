<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentController extends Controller
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
        if (!session('client_id')) {
            return redirect()->route('client.create');
        }

        //Get this client from the DB using the already existing client_id in session
        $client = Client::find(session('client_id'));

        // Send unblocked, available or canceled time slots to the calendar view.
        // Make sure they are dates not in the past i.e 'date >= today'
        $today = Carbon::today();
        $time_slots = TimeSlot::where('blocked', false)
            ->where('start_date', '>=', $today)
            ->where('status', 'available')
            ->orWhere('status', 'canceled')
            ->orderBy('start_date', 'asc')
            ->get();
            
        return view('appointment.calendar', compact('time_slots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

            Log::info($data);

            //1. Get the TimeSlot from the DB
            $time_slot = TimeSlot::where('start_date', $data['date'])->where('start_time', $data['time'])->first();
            if ($time_slot) {
                //2. Update it
                $result = $time_slot->update(['action_by' => $data['clientId'], 'status' => 'booked']);
                if ($result) {

                    // 3. Insert a new record into appointments table
                    Appointment::create([
                        'client_id' => $data['clientId'],
                        'appointment_date' => $data['date'],
                        'appointment_time' => $data['time'],
                        'duration' => $data['duration'],
                        'status' => 'pending',
                        'location' => 'Zoom',
                        'payment_status' => 'success', //This should come from Payments table
                    ]);

                    // 4. Flash a success message that can be retrieved from the view
                    session()->flash('success', 'Your appointment was successfully booked! You may close this tab or window.');

                    // 5. Return a response.
                    return response()->json(['message' => 'Data received', 'data' => $data]);
                } else {
                    session()->flash('warning', 'Error booking your appointment, try again or contact the admin.');
                    return response()->json(['error' => 'Update issues'], 404);
                }
            } else {
                session()->flash('warning', 'Error booking your appointment, try again or contact the admin.');
                return response()->json(['error' => 'No record found'], 404);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }



        //     return response()->json(['error' => 'Storing appointment'], 404);
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
