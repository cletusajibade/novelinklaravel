<?php

namespace App\Http\Controllers;

use ArielMejiaDev\LarapexCharts\PieChart;
use ArielMejiaDev\LarapexCharts\AreaChart;
use ArielMejiaDev\LarapexCharts\BarChart;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultationsCount = Client::count() ?? 0;
        $appointmentsCount = Appointment::count() ?? 0;
        $totalPayments = Payment::sum('amount') ?? 0;
        $totalPayments = '$' . number_format($totalPayments, 2);

        $clients = Client::latest()->limit(10)->get();

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

        $topCountries = Client::select('country', DB::raw('COUNT(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(3)
            ->get()
            ->toArray();

            Log::info($topCountries[0]['country']);

        /**
         * Chart Doc: https://larapex-charts.netlify.app/
         * */

         $value0 = isset($topCountries[0])? $topCountries[0]['total'] : 0;
         $value1 = isset($topCountries[1])? $topCountries[1]['total'] : 0;
         $value2 = isset($topCountries[2])? $topCountries[2]['total'] : 0;

         $country0 = isset($topCountries[0])? $topCountries[0]['country'] : "";
         $country1 = isset($topCountries[1])? $topCountries[1]['country'] : "";
         $country2 = isset($topCountries[2])? $topCountries[2]['country'] : "";
        // Pie Chart
        $pie_chart =  new PieChart();
        $pie_chart->setTitle('Clients Registration');
        $pie_chart->setSubtitle('Top 3 registrations by country.');
        $pie_chart->addData([$value0, $value1, $value2]);
        $pie_chart->setLabels([$country0, $country1, $country2]);

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


        return view('dashboard.dashboard', compact('consultationsCount', 'appointmentsCount', 'totalPayments', 'pie_chart', 'area_chart', 'bar_chart', 'clients'));
    }
}
