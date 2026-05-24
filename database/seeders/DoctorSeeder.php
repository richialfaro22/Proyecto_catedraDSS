<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        Doctor::create([
            'user_id' => 2,
            'specialty' => 'Dermatología General',
            'phone' => '7777-1111',
            'license_number' => 'DRM-001',
            'bio' => 'Especialista en enfermedades de la piel con 10 años de experiencia.',
            'is_active' => true,
        ]);

        Doctor::create([
            'user_id' => 3,
            'specialty' => 'Dermatología Estética',
            'phone' => '7777-2222',
            'license_number' => 'DRM-002',
            'bio' => 'Especialista en tratamientos estéticos y rejuvenecimiento facial.',
            'is_active' => true,
        ]);
    }
}