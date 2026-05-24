<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::with(['patient.user', 'doctor.user'])
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return MedicalRecordResource::collection($records);
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        $record = MedicalRecord::create($request->validated());

        return response()->json([
            'message' => 'Historial médico creado exitosamente',
            'medical_record' => new MedicalRecordResource($record->load(['patient.user', 'doctor.user'])),
        ], 201);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        return new MedicalRecordResource($medicalRecord->load(['patient.user', 'doctor.user', 'appointment']));
    }

    public function update(StoreMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $medicalRecord->update($request->validated());

        return response()->json([
            'message' => 'Historial médico actualizado exitosamente',
            'medical_record' => new MedicalRecordResource($medicalRecord->load(['patient.user', 'doctor.user'])),
        ]);
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return response()->json([
            'message' => 'Historial médico eliminado exitosamente',
        ]);
    }
}