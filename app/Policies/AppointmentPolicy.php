<?php

namespace App\Policies;

use App\Models\User;

class AppointmentPolicy
{
    /**
     * Create a new policy instance.
     */
        public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'doctor']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'receptionist']);
    }

    public function update(User $user)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user)
    {
        return $user->role === 'admin';
    }
}
