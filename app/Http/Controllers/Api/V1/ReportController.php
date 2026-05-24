<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Reporte general del dashboard
    public function dashboard()
    {
        return response()->json([
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'total_appointments' => Appointment::count(),
            'appointments_by_status' => Appointment::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get(),
            'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
            'appointments_this_month' => Appointment::whereMonth('appointment_date', now()->month)->count(),
        ]);
    }

    // Citas por período
    public function appointmentsByPeriod(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth();
        $to = $request->to ?? now()->endOfMonth();

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereBetween('appointment_date', [$from, $to])
            ->get();

        return response()->json([
            'period' => ['from' => $from, 'to' => $to],
            'total' => $appointments->count(),
            'appointments' => $appointments,
        ]);
    }

    // Diagnósticos más frecuentes
    public function frequentDiagnoses()
    {
        $diagnoses = MedicalRecord::selectRaw('skin_condition, count(*) as total')
            ->whereNotNull('skin_condition')
            ->groupBy('skin_condition')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'frequent_diagnoses' => $diagnoses,
        ]);
    }

    // Citas por doctor
    public function appointmentsByDoctor()
    {
        $doctors = Doctor::with('user')
            ->withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->get()
            ->map(fn($doctor) => [
                'doctor' => $doctor->user->name,
                'specialty' => $doctor->specialty,
                'total_appointments' => $doctor->appointments_count,
            ]);

        return response()->json([
            'appointments_by_doctor' => $doctors,
        ]);
    }
}