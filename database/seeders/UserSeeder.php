<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Doctores
        User::create([
            'name' => 'Dr. Carlos Mendoza',
            'email' => 'doctor1@clinica.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dra. Ana García',
            'email' => 'doctor2@clinica.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        // Recepcionista
        User::create([
            'name' => 'María López',
            'email' => 'recepcion@clinica.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
            'is_active' => true,
        ]);

        // Pacientes
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'paciente1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Laura Martínez',
            'email' => 'paciente2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
            'is_active' => true,
        ]);
    }
}