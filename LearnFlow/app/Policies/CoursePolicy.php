<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function view(User $user, Course $course): bool
    {
        return $course->is_published || $user->role === 'admin' || $course->isOwnedBy($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function update(User $user, Course $course): bool
    {
        return $user->role === 'admin' || $course->isOwnedBy($user);
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->role === 'admin' || $course->isOwnedBy($user);
    }

    public function publish(User $user, Course $course): bool
    {
        return $user->role === 'admin' || $course->isOwnedBy($user);
    }

    public function restore(User $user, Course $course): bool
    {
        return false;
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return false;
    }
}
