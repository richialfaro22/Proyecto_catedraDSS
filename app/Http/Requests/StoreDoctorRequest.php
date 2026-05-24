<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'required|string|unique:doctors,license_number,' . $this->doctor?->id,
            'bio' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El usuario es obligatorio',
            'user_id.exists' => 'El usuario no existe',
            'specialty.required' => 'La especialidad es obligatoria',
            'license_number.required' => 'El número de licencia es obligatorio',
            'license_number.unique' => 'El número de licencia ya está registrado',
        ];
    }
}