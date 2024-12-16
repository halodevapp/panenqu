<?php

namespace App\Policies;

use App\Models\ContactFormCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactFormCategoryPolicy
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
        return $user->can('CONTACT_CATEGORY_VIEW');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactFormCategory  $contactFormCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ContactFormCategory $contactFormCategory)
    {
        return $user->can('CONTACT_CATEGORY_VIEW');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('CONTACT_CATEGORY_CREATE');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactFormCategory  $contactFormCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ContactFormCategory $contactFormCategory)
    {
        return $user->can('CONTACT_CATEGORY_UPDATE');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactFormCategory  $contactFormCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ContactFormCategory $contactFormCategory)
    {
        return $user->can('CONTACT_CATEGORY_DELETE');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactFormCategory  $contactFormCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ContactFormCategory $contactFormCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactFormCategory  $contactFormCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ContactFormCategory $contactFormCategory)
    {
        //
    }
}
