<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_date' => 'sometimes|date',

            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',

            'reason' => 'sometimes|string|max:500',

            'notes' => 'nullable|string',

            'cost' => 'nullable|numeric|min:0',

            'is_paid' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'El estado no es válido',
        ];
    }
}