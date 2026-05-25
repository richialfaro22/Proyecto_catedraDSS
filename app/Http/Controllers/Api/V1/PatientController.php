<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::with('user')
            ->when($request->search, fn($q) => $q->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            ))
            ->paginate(10);

       return ApiResponse::success($patients, 'Lista de pacientes');
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return ApiResponse::success($patient, 'Paciente creado', 201);
    }

    public function show(Patient $patient)
    {
        return ApiResponse::success($patient, 'Paciente encontrado');
    }

    public function update(StorePatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

       return ApiResponse::success($patient, 'Paciente actualizado');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return ApiResponse::success(null, 'Paciente eliminado');
    }
}