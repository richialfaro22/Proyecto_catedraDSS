<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\TreatmentController;
use App\Http\Controllers\Api\V1\MedicalRecordController;
use App\Http\Controllers\Api\V1\PrescriptionController;
use App\Http\Controllers\Api\V1\ReportController;

Route::prefix('v1')->group(function () {

    // Rutas públicas (sin autenticación)
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        // Citas
        Route::apiResource('appointments', AppointmentController::class);

        // Doctores
        Route::apiResource('doctors', DoctorController::class);

        // Pacientes
        Route::apiResource('patients', PatientController::class);

        // Tratamientos
        Route::apiResource('treatments', TreatmentController::class);

        // Historial médico
        Route::apiResource('medical-records', MedicalRecordController::class);
        // Recetas
        Route::apiResource('prescriptions', PrescriptionController::class);

        // Reportes
        Route::prefix('reports')->group(function () {
            Route::get('dashboard', [ReportController::class, 'dashboard']);
            Route::get('appointments-by-period', [ReportController::class, 'appointmentsByPeriod']);
            Route::get('frequent-diagnoses', [ReportController::class, 'frequentDiagnoses']);
            Route::get('appointments-by-doctor', [ReportController::class, 'appointmentsByDoctor']);
        });

        Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
            Route::apiResource('appointments', AppointmentController::class);
        });

    }); // cierre auth:sanctum

}); // cierre prefix v1