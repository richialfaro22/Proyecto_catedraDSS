<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio',
            'patient_id.exists' => 'El paciente no existe',
            'doctor_id.required' => 'El doctor es obligatorio',
            'doctor_id.exists' => 'El doctor no existe',
            'appointment_date.required' => 'La fecha es obligatoria',
            'appointment_date.after' => 'La fecha debe ser futura',
            'reason.required' => 'El motivo de la cita es obligatorio',
        ];
    }
}