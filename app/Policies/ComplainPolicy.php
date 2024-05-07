<?php

namespace App\Policies;

use App\Models\Complain;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComplainPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == "User" || $user->role == "Admin";
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Complain $complain): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == "User";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Complain $complain): bool
    {
        return ($user->role == "User"
        && $complain->by==$user->email
        && $complain->status=="Pending") || ($user->role=="Admin")
        ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Complain $complain): bool
    {
        return $user->role == "User"
        && $complain->by==$user->email
        && $complain->status=="Pending"
        ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Complain $complain): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Complain $complain): bool
    {
        return false;
    }
}
