<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::create([
            'user_id' => 5,
            'phone' => '7888-1111',
            'birth_date' => '1990-05-15',
            'gender' => 'male',
            'address' => 'Col. Escalón, San Salvador',
            'emergency_contact' => 'María Pérez',
            'emergency_phone' => '7888-2222',
            'allergies' => 'Penicilina',
        ]);

        Patient::create([
            'user_id' => 6,
            'phone' => '7999-1111',
            'birth_date' => '1995-08-20',
            'gender' => 'female',
            'address' => 'Col. Miramonte, San Salvador',
            'emergency_contact' => 'Pedro Martínez',
            'emergency_phone' => '7999-2222',
            'allergies' => null,
        ]);
    }
}