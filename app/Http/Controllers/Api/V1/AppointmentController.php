<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\AppointmentService;
use App\Helpers\ApiResponse;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->when($request->patient_id, fn($q) => $q->where('patient_id', $request->patient_id))
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return ApiResponse::success($appointments, 'Lista de citas');
    }

    public function store(StoreAppointmentRequest $request, AppointmentService $service)
    {
        $this->authorize('create', Appointment::class);
        $appointment = $service->createAppointment($request->validated());

        return ApiResponse::success($appointment, 'Cita creada exitosamente', 201);
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return ApiResponse::success($appointment, 'Cita encontrada');
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment, AppointmentService $service)
    {
        $this->authorize('update', $appointment);
        $appointment = $service->updateAppointment($appointment, $request->validated());

        return ApiResponse::success($appointment, 'Cita actualizada');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();

        return ApiResponse::success(null, 'Cita cancelada exitosamente', 200);
    }
}