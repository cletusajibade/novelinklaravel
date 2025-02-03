<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelpers;
use App\Http\Requests\StoreTimeSlotRequest;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.admin-calendar');
    }

    public function store(Request $request)
    {
        //
    }
}
