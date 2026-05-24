<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medical_record_id' => 'required|exists:medical_records,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'medication' => 'required|string|max:255',
            'dose' => 'required|string|max:255',
            'indications' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'medical_record_id.required' => 'El historial médico es obligatorio',
            'medical_record_id.exists' => 'El historial médico no existe',
            'patient_id.required' => 'El paciente es obligatorio',
            'doctor_id.required' => 'El doctor es obligatorio',
            'medication.required' => 'El medicamento es obligatorio',
            'dose.required' => 'La dosis es obligatoria',
            'indications.required' => 'Las indicaciones son obligatorias',
            'date.required' => 'La fecha es obligatoria',
        ];
    }
}