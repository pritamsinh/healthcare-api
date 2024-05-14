<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthcareProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTime = now();
        DB::table('healthcare_professionals')->insert([
            [
                'name' => 'Dr. Dhruv Rajput',
                'specialty' => 'Cardiologist',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'name' => 'Dr. Misha Parmar',
                'specialty' => 'Pediatrician',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],            
        ]);
    }
}
