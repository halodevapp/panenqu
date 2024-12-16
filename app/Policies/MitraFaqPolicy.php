<?php

namespace App\Policies;

use App\Models\MitraFaq;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MitraFaqPolicy
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
        return $user->can('MITRA_FAQ_VIEW');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MitraFaq $mitraFaq)
    {
        return $user->can('MITRA_FAQ_VIEW');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('MITRA_FAQ_CREATE');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MitraFaq $mitraFaq)
    {
        return $user->can('MITRA_FAQ_UPDATE');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MitraFaq $mitraFaq)
    {
        return $user->can('MITRA_FAQ_DELETE');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MitraFaq $mitraFaq)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MitraFaq  $mitraFaq
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MitraFaq $mitraFaq)
    {
        //
    }
}
