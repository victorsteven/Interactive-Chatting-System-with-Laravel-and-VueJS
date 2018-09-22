<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //the person signed in does he have the permission to update the user in question?

    public function update(User $user, User $signedUser)
    {
        return $signedUser->id === $user->id;
    }
}
