<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::with(['patient.user', 'doctor.user'])
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ApiResponse::success($records, 'Historial clínico');
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        $record = MedicalRecord::create($request->validated());

       return ApiResponse::success($record, 'Registro creado', 201);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        return ApiResponse::success($record, 'Registro encontrado');
    }

    public function update(StoreMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $medicalRecord->update($request->validated());

        return ApiResponse::success($record, 'Registro actualizado');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return ApiResponse::success(null, 'Registro eliminado');
    }
}