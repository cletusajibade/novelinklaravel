<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

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

        // Send unbooked/available time slots to the calendar view
        $time_slots = TimeSlot::where('booked_by', null)->where('is_available', 1)->get();
        return view('appointment.calendar', compact('time_slots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //Get the TimeSlot from the DB
        $time_slot = TimeSlot::where('start_date', $request->date)->where('start_time', $request->timeSlot)->first();
        if ($time_slot) {
            $result = $time_slot->update(['booked_by' => $request->clientId]);
            if ($result) {
                // Flash a success message that can be retrieved from the view
                session()->flash('success', 'Your appointment was successfully booked!');

                return response()->json(['message' => 'Data received', 'data' => $data]);
            } else {
                session()->flash('warning', 'Error booking your appointment, try again or contact the admin.');
                return response()->json(['error' => 'No record found'], 404);
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
