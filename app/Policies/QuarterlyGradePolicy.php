<?php

namespace App\Policies;

use App\Models\QuarterlyGrade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuarterlyGradePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view all grades
        if ($user->isAdmin()) {
            return true;
        }

        // Teachers can view grades for their classes
        if ($user->role === 'teacher') {
            return true;
        }

        // Students can view their own grades
        if ($user->role === 'student') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Super admin can view any grade
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Registrar admin can view any grade for approval
        if ($user->role === 'registrar_admin') {
            return true;
        }

        // Academic admin can view any grade
        if ($user->role === 'academic_admin') {
            return true;
        }

        // Technical admin can view for audit purposes
        if ($user->role === 'technical_admin') {
            return true;
        }

        // Teachers can view grades for their classes
        if ($user->role === 'teacher') {
            return $quarterlyGrade->classSchedule && 
                   $quarterlyGrade->classSchedule->teacher_id === $user->id;
        }

        // Students can only view their own APPROVED grades
        if ($user->role === 'student') {
            return $quarterlyGrade->student_id === $user->id && 
                   $quarterlyGrade->status === 'Approved';
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only teachers can create/input grades
        return $user->role === 'teacher';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Super admin can update any grade
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Registrar admin can update (override) grades with audit log
        if ($user->role === 'registrar_admin') {
            return true;
        }

        // Teachers can update their own class grades only if not yet submitted/approved
        if ($user->role === 'teacher') {
            $isOwnClass = $quarterlyGrade->classSchedule && 
                          $quarterlyGrade->classSchedule->teacher_id === $user->id;
            $isEditable = in_array($quarterlyGrade->status, ['Draft', 'Returned']);
            
            return $isOwnClass && $isEditable;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Only super admin can delete grades, and only if not approved
        if ($user->isSuperAdmin()) {
            return $quarterlyGrade->status !== 'Approved';
        }

        return false;
    }

    /**
     * Determine whether the user can submit grades for approval.
     */
    public function submit(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Only teachers can submit grades for their own classes
        if ($user->role === 'teacher') {
            $isOwnClass = $quarterlyGrade->classSchedule && 
                          $quarterlyGrade->classSchedule->teacher_id === $user->id;
            $canSubmit = in_array($quarterlyGrade->status, ['Draft', 'Returned']);
            
            return $isOwnClass && $canSubmit;
        }

        return false;
    }

    /**
     * Determine whether the user can approve grades.
     */
    public function approve(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Only registrar admin and super admin can approve grades
        return $user->isSuperAdmin() || $user->role === 'registrar_admin';
    }

    /**
     * Determine whether the user can return grades to teacher.
     */
    public function return(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Only registrar admin and super admin can return grades
        return $user->isSuperAdmin() || $user->role === 'registrar_admin';
    }

    /**
     * Determine whether the user can override/edit approved grades.
     */
    public function override(User $user, QuarterlyGrade $quarterlyGrade): bool
    {
        // Only registrar admin and super admin can override approved grades
        // This requires an audit log entry
        return $user->isSuperAdmin() || $user->role === 'registrar_admin';
    }
}
