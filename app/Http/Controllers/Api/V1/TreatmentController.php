<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreatmentRequest;
use App\Http\Resources\TreatmentResource;
use App\Models\Treatment;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

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

        return ApiResponse::success($treatments, 'Lista de tratamientos');
    }

    public function store(StoreTreatmentRequest $request)
    {
        $treatment = Treatment::create($request->validated());

        return ApiResponse::success($treatment, 'Tratamiento creado', 201);
    }

    public function show(Treatment $treatment)
    {
        return ApiResponse::success($treatment, 'Tratamiento encontrado');
    }

    public function update(StoreTreatmentRequest $request, Treatment $treatment)
    {
        $treatment->update($request->validated());

        return ApiResponse::success($treatment, 'Tratamiento actualizado');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

       return ApiResponse::success(null, 'Tratamiento eliminado');
    }
}