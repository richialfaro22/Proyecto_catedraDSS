<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with('user')
            ->when($request->specialty, fn($q) => $q->where('specialty', 'like', "%{$request->specialty}%"))
            ->when($request->is_active, fn($q) => $q->where('is_active', $request->is_active))
            ->paginate(10);

        return DoctorResource::collection($doctors);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->validated());

        return response()->json([
            'message' => 'Doctor creado exitosamente',
            'doctor' => new DoctorResource($doctor->load('user')),
        ], 201);
    }

    public function show(Doctor $doctor)
    {
        return new DoctorResource($doctor->load(['user', 'appointments']));
    }

    public function update(StoreDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        return response()->json([
            'message' => 'Doctor actualizado exitosamente',
            'doctor' => new DoctorResource($doctor->load('user')),
        ]);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([
            'message' => 'Doctor eliminado exitosamente',
        ]);
    }
}