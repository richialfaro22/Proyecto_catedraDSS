<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with('user')
            ->when($request->specialty, fn($q) => $q->where('specialty', 'like', "%{$request->specialty}%"))
            ->when($request->is_active, fn($q) => $q->where('is_active', $request->is_active))
            ->paginate(10);

       return ApiResponse::success($doctors, 'Lista de doctores');
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->validated());

        return ApiResponse::success($doctor, 'Doctor creado exitosamente', 201);
    }

    public function show(Doctor $doctor)
    {
        return ApiResponse::success($doctor->load(['user', 'appointments']), 'Doctor encontrado');
    }

    public function update(StoreDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        return ApiResponse::success($doctor->load('user'), 'Doctor actualizado exitosamente');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return ApiResponse::success(null, 'Doctor eliminado');
    }
}