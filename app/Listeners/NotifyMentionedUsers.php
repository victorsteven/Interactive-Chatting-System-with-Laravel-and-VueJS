<?php

namespace App\Listeners;

use App\User;

use App\Events\ThreadReceivedNewReply;

use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        //check if usernames are mentioned in the reply body
            //match all stuffs that start with @ followed by none-space or dot, one or more times

            
            //  preg_match_all('/@([^\s\.,]+)/', $this->body, $matches);
            // $names = $matches[1];
            // dd($names);

            $mentionedUsers = $event->reply->mentionedUsers();

            

            //foreach name mentioned, notify them
            // foreach( $mentionedUsers as $name){
            //     $user = User::whereName($name)->first();

            //     if($user){
            //         $user->notify(new YouWereMentioned($event->reply));
            //     }
            // }

            //OR:

            //we could collect the array of mentioned users
            // collect($event->reply->mentionedUsers())
            //     //we map down to a user instance: go throuh them and return what u get so that they can be filtered:
            //     ->map(function($name){
            //         return User::where('name', $name)->first();
            //         //the above will return a user or null
            //     })
            //     //remove all null values, and give us only user instances that exist in our database
            //     ->filter()
            //     //for each legit value, notify 
            //     ->each(function($user) use ($event) {
            //         $user->notify(new YouWereMentioned($event->reply));
            //     });

                //OR

                //give me the name of all users u have, provided the name column exists in the array, this means even if we give a name that is not in the database, we no longer need to filter for null values

                //we get the user collection, so no need to use the "collect()" method.  
                //we dont need to map over them because we already have user instances.
                //we dont need to call filter method because we are getting our data straight from the the database, so no need to check null values
                
                User::whereIn('name', $event->reply->mentionedUsers())
                    ->get()
                    ->each(function($user) use ($event) {
                    $user->notify(new YouWereMentioned($event->reply));
                });
                // dd($users);
     
    }
}
