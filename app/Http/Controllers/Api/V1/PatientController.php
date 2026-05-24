<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

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

        return PatientResource::collection($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return response()->json([
            'message' => 'Paciente creado exitosamente',
            'patient' => new PatientResource($patient->load('user')),
        ], 201);
    }

    public function show(Patient $patient)
    {
        return new PatientResource($patient->load(['user', 'appointments', 'treatments', 'medicalRecords']));
    }

    public function update(StorePatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

        return response()->json([
            'message' => 'Paciente actualizado exitosamente',
            'patient' => new PatientResource($patient->load('user')),
        ]);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->json([
            'message' => 'Paciente eliminado exitosamente',
        ]);
    }
}