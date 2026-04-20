<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function view(User $user, Module $module): bool
    {
        return $module->course->is_published || $user->role === 'admin' || $module->course->isOwnedBy($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'host'], true);
    }

    public function update(User $user, Module $module): bool
    {
        return $user->role === 'admin' || $module->course->isOwnedBy($user);
    }

    public function delete(User $user, Module $module): bool
    {
        return $user->role === 'admin' || $module->course->isOwnedBy($user);
    }

    public function restore(User $user, Module $module): bool
    {
        return false;
    }

    public function forceDelete(User $user, Module $module): bool
    {
        return false;
    }
}
