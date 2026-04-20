<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function view(User $user, Lesson $lesson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        $course = $lesson->module->course;

        if ($course->isOwnedBy($user) || $lesson->is_free) {
            return true;
        }

        if ($course->relationLoaded('enrollments')) {
            return $course->enrollments
                ->contains(fn ($enrollment) => $enrollment->user_id === $user->id && $enrollment->status === 'accepted');
        }

        return $course->enrollments()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->role === 'admin' || $lesson->module->course->isOwnedBy($user);
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->role === 'admin' || $lesson->module->course->isOwnedBy($user);
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return false;
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return false;
    }
}
