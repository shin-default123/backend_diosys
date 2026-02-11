<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baptism;
use App\Models\Confirmation;
use App\Models\Marriage;
use App\Models\Death;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'Baptism' => $this->getGrowth(Baptism::class),
            'Confirmation' => $this->getGrowth(Confirmation::class),
            'Matrimony' => $this->getGrowth(Marriage::class),
            'Memorial' => $this->getGrowth(Death::class), 
        ];

        $years = range(Carbon::now()->year - 9, Carbon::now()->year + 2); 
        $chartData = [];

        foreach ($years as $year) {
            $chartData[] = [
                'year' => (string)$year,
                'Baptism' => Baptism::whereYear('date', $year)->count(),
                'Confirmation' => Confirmation::whereYear('date', $year)->count(),
                'Matrimony' => Marriage::whereYear('date', $year)->count(),
                'Memorial' => Death::whereYear('date', $year)->count(),
            ];
        }

        $pyramidData = $this->getDemographics();

        return response()->json([
            'cards' => $stats,
            'lineChart' => $chartData,
            'pyramid' => $pyramidData
        ]);
    }

    private function getGrowth($model)
    {
        $currentMonth = $model::whereMonth('date', Carbon::now()->month)
                              ->whereYear('date', Carbon::now()->year)
                              ->count();

        $lastMonth = $model::whereMonth('date', Carbon::now()->subMonth()->month)
                           ->whereYear('date', Carbon::now()->subMonth()->year)
                           ->count();

        if ($lastMonth == 0) {
            $growth = $currentMonth > 0 ? 100 : 0;
        } else {
            $growth = (($currentMonth - $lastMonth) / $lastMonth) * 100;
        }

        return [
            'value' => $currentMonth,
            'growth' => round($growth, 1)
        ];
    }

    private function getDemographics()
    { 
        $ranges = ['0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74', '75-79', '80+'];
        $data = [];

        foreach ($ranges as $range) {
            // Determine Age Bounds
            if ($range === '80+') {
                $minAge = 80;
                $maxAge = 200;
            } else {
                [$minAge, $maxAge] = explode('-', $range);
            }

            // Calculate Date Bounds for Query
            $startDate = Carbon::now()->subYears($maxAge + 1)->format('Y-m-d');
            $endDate = Carbon::now()->subYears($minAge)->format('Y-m-d');

            $males = Baptism::where('sex', 'Male')
                            ->whereBetween('date', [$startDate, $endDate])
                            ->count();
                            
            $females = Baptism::where('sex', 'Female')
                            ->whereBetween('date', [$startDate, $endDate])
                            ->count();

            $data[] = [
                'age' => $range,
                'male' => -abs($males), 
                'female' => $females
            ];
        }

        return $data;
    }
}