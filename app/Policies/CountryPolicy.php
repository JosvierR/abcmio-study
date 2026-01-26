<?php

namespace App\Policies;

use App\User;
use App\Country;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any countries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can view the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can update the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function update(User $user, Country $country)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can delete the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function delete(User $user, Country $country)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can restore the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function restore(User $user, Country $country)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }

    /**
     * Determine whether the user can permanently delete the country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function forceDelete(User $user, Country $country)
    {
        return $user->type === 'admin' || $user->type === 'super';
    }
}
