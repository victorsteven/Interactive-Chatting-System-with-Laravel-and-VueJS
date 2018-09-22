<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;


class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(){
        parent::setUp();

        $this->signIn();
    }

    function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(){


        // why we call subscribe() like this is because, we returned its instance
        $thread = create('App\Thread')->subscribe();

        //*we have loaded this relationship, 
        $this->assertCount(0, auth()->user()->notifications);

        //then each time a new reply is left...
        //*so we make a change
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here'
        ]);

        //A notification should be prepared for the user, when a reply is left.
        //*we should use fresh() to load it
        $this->assertCount(0, auth()->user()->notifications);
        

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);


    }

    function test_a_user_can_fetch_all_unread_notifications(){

        create(DatabaseNotification::class);

        // $thread = create('App\Thread')->subscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'some reply here'
        // ]);

        $user = auth()->user();

        $response = $this->getJson("/profiles/{$user->name}/notifications/")->json();

        $this->assertCount(1, $response);

    }

    public function test_a_user_can_mark_notification_as_read(){

        create(DatabaseNotification::class);

        // $thread = create('App\Thread')->subscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'some reply here'
        // ]);

        //get the notification where the "read_at" column is equal to null.
        //note we can also use "notifications", we just want only the unread

        tap(auth()->user(), function($user){

            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;
            
            $userName = $user->name;

            $this->delete("/profiles/{$userName}/notifications/{$notificationId}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);

            // $this->delete("/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id );

            // $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
