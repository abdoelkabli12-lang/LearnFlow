<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->id === $enrollment->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->id === $enrollment->user_id;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->id === $enrollment->user_id;
    }

    public function restore(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }
}
