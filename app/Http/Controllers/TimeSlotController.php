<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelpers;
use App\Http\Requests\StoreTimeSlotRequest;
use App\Http\Requests\UpdateTimeSlotRequest;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timeslots = TimeSlot::latest()->get();
        return view('dashboard.time-slots', compact('timeslots'));
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
    public function store(StoreTimeSlotRequest $request)
    {
        $data = $request->validated();

        $exclude_weekends = isset($data['exclude_weekends']) ? true : false;

        // Pre-generate time slots and store them in the database
        $result = UtilHelpers::getDatesAndTimes($data['start_date'], $data['end_date'], $data['start_time'], $data['end_time'], $exclude_weekends, $data['duration']);
        // dd($result);
        foreach ($result as $entry) {
            $date = $entry['date'];
            foreach ($entry['times'] as $time) {
                // check if start_date and start_time combination already exists in the database
                $exists = TimeSlot::where('start_date', $date)->where('start_time', $time)->exists();
                if (!$exists) {
                    $timeEnd = Carbon::createFromFormat('H:i', $time)->addMinutes($data['duration'] * 60);
                    TimeSlot::Create([
                        'duration' => $data['duration'],
                        'start_date' => $date,
                        'start_time' => $time,
                        'end_date' => $date,
                        'end_time' => $timeEnd,
                        'is_available' => $data['is_available'] ?? true,
                        'booked_by' => $data['booked_by'] ?? null
                    ]);
                }
                else
                {
                    return redirect()->back()->with('warning', 'Start Date and Start Time slots already exist. Consider updating existing slots.');
                }
            }
        }

        return redirect()->back()->with('success', 'Time slotes created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSlot $timeSlot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeSlot $timeSlot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimeSlotRequest $request, TimeSlot $timeSlot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $timeSlot)
    {
        //
    }
}
