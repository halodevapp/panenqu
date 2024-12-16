<?php

namespace App\Policies;

use App\Models\ContactForm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactFormPolicy
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
        return $user->can('CONTACT_VIEW');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ContactForm $contactForm)
    {
        return $user->can('CONTACT_VIEW');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('CONTACT_CREATE');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ContactForm $contactForm)
    {
        return $user->can('CONTACT_UPDATE');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ContactForm $contactForm)
    {
        return $user->can('CONTACT_DELETE');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ContactForm $contactForm)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ContactForm  $contactForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ContactForm $contactForm)
    {
        //
    }
}
