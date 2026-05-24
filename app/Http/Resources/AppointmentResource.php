<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date,
            'status' => $this->status,
            'reason' => $this->reason,
            'notes' => $this->notes,
            'cost' => $this->cost,
            'is_paid' => $this->is_paid,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'treatment' => new TreatmentResource($this->whenLoaded('treatment')),
            'medical_record' => new MedicalRecordResource($this->whenLoaded('medicalRecord')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}