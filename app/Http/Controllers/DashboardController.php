<?php

namespace App\Http\Controllers;

use App\Models\Advisory;
use App\Models\Municipality;
use App\Services\RiskAssessmentService;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        return $request->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('officer.dashboard');
    }

    public function admin(RiskAssessmentService $ai, WeatherService $weather)
    {
        return view('admin.dashboard', $this->weatherData($ai, $weather) + [
            'pending' => Advisory::where('status', 'pending')->count(),
            'approved' => Advisory::where('status', 'approved')->count(),
        ]);
    }

    public function officer(Request $request, RiskAssessmentService $ai, WeatherService $weather)
    {
        $mine = $request->user()->advisories();

        return view('officer.dashboard', $this->weatherData($ai, $weather) + [
            'pending' => (clone $mine)->where('status', 'pending')->count(),
            'approved' => (clone $mine)->where('status', 'approved')->count(),
        ]);
    }

    public function refresh(WeatherService $weather)
    {
        return back()->with('status', rescue(fn () => $weather->fetch(), false, false)
            ? 'Weather updated.'
            : 'Could not get weather. Check your internet connection.');
    }

    private function weatherData(RiskAssessmentService $ai, WeatherService $weather): array
    {
        $weather->fetchIfOld();

        $municipalities = Municipality::orderBy('name')->get()
            ->each(fn ($m) => $m->risk = $m->weather_updated_at ? $ai->risk($m) : '-');

        return [
            'municipalities' => $municipalities,
            'updated' => Municipality::max('weather_updated_at'),
        ];
    }
}
