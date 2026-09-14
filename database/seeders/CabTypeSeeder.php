<?php

namespace Database\Seeders;

use App\Models\CabType;
use Illuminate\Database\Seeder;

class CabTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Hire Driver Only',
            'Hatchback (WagonR / Indica)',
            'Sedan (Dzire / Etios)',
            'SUV (Ertiga / XL6)',
            'Premium SUV (Innova Crysta)',
            'Luxury (Fortuner / BMW)',
            'Tempo Traveller (12 Seater)',
            'Tempo Traveller (17 Seater)',
            'Bus / Mini Bus',
        ];

        foreach ($types as $type) {
            CabType::firstOrCreate(['name' => $type], ['is_active' => true]);
        }
    }
}
