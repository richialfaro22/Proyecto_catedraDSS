<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    //Crear cita
    public function createAppointment(array $data)
    {
        // Validar disponibilidad del doctor
        if ($this->isDoctorUnavailable(
            $data['doctor_id'],
            $data['appointment_date']
        )) {
            throw ValidationException::withMessages([
                'doctor_id' => ['El doctor no está disponible en esa fecha']
            ]);
        }

        return Appointment::create($data);
    }

    //Actualizar cita
    public function updateAppointment(Appointment $appointment, array $data)
    {
        if (isset($data['doctor_id']) || isset($data['appointment_date'])) {

            $doctorId = $data['doctor_id'] ?? $appointment->doctor_id;
            $date = $data['appointment_date'] ?? $appointment->appointment_date;

            if ($this->isDoctorUnavailable($doctorId, $date, $appointment->id)) {
                throw ValidationException::withMessages([
                    'doctor_id' => ['El doctor no está disponible en esa fecha']
                ]);
            }
        }

        $appointment->update($data);

        return $appointment;
    }

    // Verifica disponibilidad del doctor
    private function isDoctorUnavailable($doctorId, $date, $ignoreId = null)
    {
        return Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}