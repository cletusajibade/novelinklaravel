<?php

namespace App\Policies;

use App\Models\ConsultationPackages;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsultationPackagesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ConsultationPackages $consultationPackages): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ConsultationPackages $consultationPackages): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ConsultationPackages $consultationPackages): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ConsultationPackages $consultationPackages): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ConsultationPackages $consultationPackages): bool
    {
        return false;
    }
}
