<?php

namespace App\Policies;

use App\User;
use App\Reply;

use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
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

    public function update(User $user, Reply $reply){

      return  $reply->user_id == $user->id;
    }

    public function create(User $user){

        //if their last reply was not just published, they are good to go

        //in the case the user does not have a reply, this will return null
        // return ! $user->lastReply->wasJustPublished();

        //since user reply is eager, we need a fresh instance
        $lastReply = $user->fresh()->lastReply;

        //This is shorter
        // return ! $lastReply || ! $lastReply->wasJustPublished();

        //if the user is new without any last reply, he is good to go
        if(! $lastReply) return true; 

        //if the existing user's reply was not just published now, he is good to go
        return ! $lastReply->wasJustPublished();
    }
}
