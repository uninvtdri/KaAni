<?php

namespace App\Services;

use App\Models\Municipality;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function fetch(): bool
    {
        $towns = Municipality::orderBy('id')->get();

        $response = Http::timeout(15)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $towns->pluck('latitude')->implode(','),
            'longitude' => $towns->pluck('longitude')->implode(','),
            'current' => 'temperature_2m,relative_humidity_2m',
            'daily' => 'precipitation_sum',
            'timezone' => 'Asia/Manila',
            'forecast_days' => 1,
        ]);

        if ($response->failed()) {
            return false;
        }

        $results = $towns->count() === 1 ? [$response->json()] : $response->json();

        foreach ($towns as $i => $town) {
            $town->update([
                'temp_c' => $results[$i]['current']['temperature_2m'],
                'humidity' => $results[$i]['current']['relative_humidity_2m'],
                'rainfall_mm' => $results[$i]['daily']['precipitation_sum'][0] ?? 0,
                'weather_updated_at' => now(),
            ]);
        }

        return true;
    }

    public function fetchIfOld(): void
    {
        $last = Municipality::max('weather_updated_at');

        if (! $last || now()->diffInMinutes($last, true) >= 60) {
            rescue(fn () => $this->fetch(), report: false);
        }
    }
}
