<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultationsCount = Consultation::count() ?? 0;
        $appointmentsCount = Appointment::count() ?? 0;
        $totalPayments = Payment::sum('amount') ?? 0;
        $totalPayments = '$' . number_format($totalPayments, 2);
        return view('dashboard.dashboard', compact('consultationsCount', 'appointmentsCount', 'totalPayments'));
    }
}
