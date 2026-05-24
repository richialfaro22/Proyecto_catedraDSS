<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Resources\TreatmentResource;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $treatments = Treatment::with(['patient.user', 'doctor.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return TreatmentResource::collection($treatments);
    }

    public function store(StoreTreatmentRequest $request)
    {
        $treatment = Treatment::create($request->validated());

        return response()->json([
            'message' => 'Tratamiento creado exitosamente',
            'treatment' => new TreatmentResource($treatment->load(['patient.user', 'doctor.user'])),
        ], 201);
    }

    public function show(Treatment $treatment)
    {
        return new TreatmentResource($treatment->load(['patient.user', 'doctor.user', 'appointment']));
    }

    public function update(StoreTreatmentRequest $request, Treatment $treatment)
    {
        $treatment->update($request->validated());

        return response()->json([
            'message' => 'Tratamiento actualizado exitosamente',
            'treatment' => new TreatmentResource($treatment->load(['patient.user', 'doctor.user'])),
        ]);
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return response()->json([
            'message' => 'Tratamiento eliminado exitosamente',
        ]);
    }
}