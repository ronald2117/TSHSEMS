<?php

namespace App\Policies;

use App\Models\ClassSchedule;
use App\Models\User;

class ClassSchedulePolicy
{
    /**
     * Determine if the user can view the class schedule
     */
    public function view(User $user, ClassSchedule $classSchedule): bool
    {
        if ($user->isTeacher()) {
            return $classSchedule->teacher_id === $user->id;
        }

        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the class schedule
     */
    public function update(User $user, ClassSchedule $classSchedule): bool
    {
        if ($user->isTeacher()) {
            return $classSchedule->teacher_id === $user->id;
        }

        return $user->isAdmin();
    }
}
