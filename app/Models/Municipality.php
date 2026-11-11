<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [
        'name', 'latitude', 'longitude',
        'temp_c', 'rainfall_mm', 'humidity', 'weather_updated_at',
    ];

    protected function casts(): array
    {
        return ['weather_updated_at' => 'datetime'];
    }
}
