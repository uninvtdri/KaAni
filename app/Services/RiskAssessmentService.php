<?php

namespace App\Services;

use App\Models\Municipality;

class RiskAssessmentService
{
    public const CROPS = ['Rice', 'Corn'];

    public function risk(Municipality $m): string
    {
        if ($m->rainfall_mm >= 50 || $m->temp_c >= 35) {
            return 'High';
        }
        if ($m->rainfall_mm >= 20 || $m->temp_c >= 32 || $m->humidity >= 85) {
            return 'Moderate';
        }

        return 'Low';
    }

    public function draft(Municipality $m, string $crop): string
    {
        $tips = [];
        if ($m->rainfall_mm >= 20) {
            $tips[] = 'Clear canals and drainage. Delay fertilizer application until after the rain.';
        }
        if ($m->temp_c >= 32) {
            $tips[] = 'Keep enough water in the field. Do field work early morning or late afternoon.';
        }
        if ($m->humidity >= 85) {
            $tips[] = 'Check for pests and plant diseases.';
        }
        if (! $tips) {
            $tips[] = 'Weather is normal. Continue regular farm work.';
        }

        return "{$this->risk($m)} risk for {$crop} in {$m->name}.\n"
            ."Weather: {$m->temp_c}°C, {$m->rainfall_mm} mm rain, {$m->humidity}% humidity.\n\n"
            .implode("\n", $tips);
    }
}
