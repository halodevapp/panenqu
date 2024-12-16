<?php

namespace App\Policies;

use App\Models\MitraForm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MitraFormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('MITRA_FORM_VIEW');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MitraForm $mitraForm)
    {
        return $user->can('MITRA_FORM_VIEW');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MitraForm $mitraForm)
    {
        return $user->can('MITRA_FORM_UPDATE');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MitraForm $mitraForm)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MitraForm $mitraForm)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraForm  $mitraForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MitraForm $mitraForm)
    {
        //
    }

    public function export(User $user)
    {
        return $user->can('MITRA_FORM_EXPORT');
    }
}
