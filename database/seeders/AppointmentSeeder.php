<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        Appointment::create([
            'patient_id' => 1,
            'doctor_id' => 1,
            'appointment_date' => '2026-06-01 09:00:00',
            'status' => 'confirmed',
            'reason' => 'Consulta por acné severo',
            'notes' => 'Paciente con historial de acné desde los 15 años',
            'cost' => 50.00,
            'is_paid' => true,
        ]);

        Appointment::create([
            'patient_id' => 2,
            'doctor_id' => 2,
            'appointment_date' => '2026-06-02 10:00:00',
            'status' => 'pending',
            'reason' => 'Tratamiento de manchas en la piel',
            'notes' => null,
            'cost' => 75.00,
            'is_paid' => false,
        ]);

        Appointment::create([
            'patient_id' => 1,
            'doctor_id' => 2,
            'appointment_date' => '2026-06-05 11:00:00',
            'status' => 'pending',
            'reason' => 'Revisión de tratamiento anterior',
            'notes' => null,
            'cost' => 50.00,
            'is_paid' => false,
        ]);
    }
}