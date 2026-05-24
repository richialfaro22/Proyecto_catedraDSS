<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'instructions' => 'nullable|string',
            'status' => 'sometimes|in:active,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_id.required' => 'La cita es obligatoria',
            'appointment_id.exists' => 'La cita no existe',
            'doctor_id.required' => 'El doctor es obligatorio',
            'patient_id.required' => 'El paciente es obligatorio',
            'name.required' => 'El nombre del tratamiento es obligatorio',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.after' => 'La fecha de fin debe ser posterior a la de inicio',
        ];
    }
}