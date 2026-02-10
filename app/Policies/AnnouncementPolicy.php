<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All admins can view announcements
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        // All admins can view announcements
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, academic admin, and registrar admin can create announcements
        return $user->isSuperAdmin() || 
               $user->role === 'academic_admin' || 
               $user->role === 'registrar_admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        // Super admins can edit any announcement
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Academic and registrar admins can only edit their own announcements
        if ($user->role === 'academic_admin' || $user->role === 'registrar_admin') {
            return $announcement->author_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        // Super admins can delete any announcement
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Academic and registrar admins can only delete their own announcements
        if ($user->role === 'academic_admin' || $user->role === 'registrar_admin') {
            return $announcement->author_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return false;
    }
}
