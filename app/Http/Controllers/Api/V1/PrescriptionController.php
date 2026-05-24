<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $prescriptions = Prescription::with(['patient.user', 'doctor.user', 'medicalRecord'])
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->orderBy('date', 'desc')
            ->paginate(10);

        return PrescriptionResource::collection($prescriptions);
    }

    public function store(StorePrescriptionRequest $request)
    {
        $prescription = Prescription::create($request->validated());

        return response()->json([
            'message' => 'Receta creada exitosamente',
            'prescription' => new PrescriptionResource($prescription->load(['patient.user', 'doctor.user'])),
        ], 201);
    }

    public function show(Prescription $prescription)
    {
        return new PrescriptionResource($prescription->load(['patient.user', 'doctor.user', 'medicalRecord']));
    }

    public function update(StorePrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());

        return response()->json([
            'message' => 'Receta actualizada exitosamente',
            'prescription' => new PrescriptionResource($prescription->load(['patient.user', 'doctor.user'])),
        ]);
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return response()->json([
            'message' => 'Receta eliminada exitosamente',
        ]);
    }
}