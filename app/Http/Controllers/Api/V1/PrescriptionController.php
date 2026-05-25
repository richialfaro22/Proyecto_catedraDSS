<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $prescriptions = Prescription::with(['patient.user', 'doctor.user', 'medicalRecord'])
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->orderBy('date', 'desc')
            ->paginate(10);

        return ApiResponse::success($prescriptions, 'Lista de recetas');
    }

    public function store(StorePrescriptionRequest $request)
    {
        $prescription = Prescription::create($request->validated());

        return ApiResponse::success($prescription, 'Receta creada', 201);
    }

    public function show(Prescription $prescription)
    {
        return ApiResponse::success($prescription, 'Receta encontrada');
    }

    public function update(StorePrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());

        return ApiResponse::success($prescription, 'Receta actualizada');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return ApiResponse::success(null, 'Receta eliminada');
    }
}