<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Provincial Admin',
            'email' => 'admin@kaani.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Officer Juan',
            'email' => 'officer@kaani.test',
            'password' => 'password',
            'role' => 'officer',
        ]);

        User::create([
            'name' => 'Officer Maria',
            'email' => 'officer2@kaani.test',
            'password' => 'password',
            'role' => 'officer',
        ]);

        $towns = [
            ['Tuguegarao City', 17.6132, 121.7270],
            ['Aparri', 18.3567, 121.6402],
            ['Tuao', 17.7364, 121.4547],
            ['Solana', 17.6517, 121.6914],
            ['Gattaran', 18.0617, 121.6436],
            ['Baggao', 17.9728, 121.7714],
            ['Lal-lo', 18.2000, 121.6622],
            ['Sanchez-Mira', 18.5617, 121.2350],
        ];

        foreach ($towns as [$name, $lat, $lng]) {
            Municipality::create(['name' => $name, 'latitude' => $lat, 'longitude' => $lng]);
        }
    }
}
