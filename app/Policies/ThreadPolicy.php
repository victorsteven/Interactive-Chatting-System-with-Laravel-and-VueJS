<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */

    //this means the user have instant access to delete any thread of his choice, whether he created it or not
    //this will be moved to AuthServiceProvider
    // public function before($user){
    //     if($user->name === "JohnDoe"){
    //         return true;
    //     }
    // }

    public function view(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        //if the thread user_id is equal to the auth user id
        return $thread->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can restore the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function restore(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function forceDelete(User $user, Thread $thread)
    {
        //
    }
}
