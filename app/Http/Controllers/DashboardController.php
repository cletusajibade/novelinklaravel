<?php

namespace App\Http\Controllers;

use ArielMejiaDev\LarapexCharts\PieChart;
use ArielMejiaDev\LarapexCharts\AreaChart;
use ArielMejiaDev\LarapexCharts\BarChart;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Payment;
use Carbon\Carbon;
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

        $topFiveDates = Payment::orderBy('created_at', 'desc')
            ->take(5)
            ->pluck('created_at')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        $topFiveAmounts = Payment::orderBy('created_at', 'desc')
            ->take(5)
            ->pluck('amount')
            ->toArray();

        // Pie Chart
        $chart =  new PieChart();
        $chart->setTitle('Top 3 scorers of the team.');
        $chart->setSubtitle('Season 2021.');
        $chart->addData([20, 24, 30]);
        $chart->setLabels(['Player 7', 'Player 10', 'Player 9']);

        // Area Chart
        $area_chart = new AreaChart();
        $area_chart->setTitle('Sales during 2021.');
        $area_chart->setSubtitle('Physical sales vs Digital sales.');
        $area_chart->addData('Physical sales', [40, 93, 35, 42, 18, 82]);
        $area_chart->addData('Digital sales', [70, 29, 77, 28, 55, 45]);
        $area_chart->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);


        // Bar Chart
        $bar_chart = new BarChart();
        $bar_chart->setTitle('Recent Payments');
        $bar_chart->setSubtitle('Five most recent payments');
        $bar_chart->addData('Payment', $topFiveAmounts);
        // $bar_chart->addData('Boston', [7, 3, 8, 2, 6, 4]);
        $bar_chart->setXAxis($topFiveDates);


        return view('dashboard.dashboard', compact('consultationsCount', 'appointmentsCount', 'totalPayments', 'chart', 'area_chart', 'bar_chart'));
    }
}
