<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return AppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());

        return response()->json([
            'message' => 'Cita creada exitosamente',
            'appointment' => new AppointmentResource($appointment->load(['patient.user', 'doctor.user'])),
        ], 201);
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load(['patient.user', 'doctor.user', 'treatment', 'medicalRecord']));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return response()->json([
            'message' => 'Cita actualizada exitosamente',
            'appointment' => new AppointmentResource($appointment->load(['patient.user', 'doctor.user'])),
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Cita cancelada exitosamente',
        ]);
    }
}