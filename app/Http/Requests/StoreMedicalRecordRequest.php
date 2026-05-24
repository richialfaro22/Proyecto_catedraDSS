<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
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
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'nullable|string',
            'observations' => 'nullable|string',
            'skin_condition' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio',
            'doctor_id.required' => 'El doctor es obligatorio',
            'appointment_id.required' => 'La cita es obligatoria',
            'diagnosis.required' => 'El diagnóstico es obligatorio',
        ];
    }
}