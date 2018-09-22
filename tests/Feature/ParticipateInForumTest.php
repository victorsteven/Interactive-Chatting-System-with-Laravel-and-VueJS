<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForum extends TestCase
{
    
    use RefreshDatabase;

    function test_unauthenticated_users_may_not_add_replies(){

        // $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->withExceptionHandling()
            ->post('/threads/channel/1/replies', [])
            ->assertRedirect('/login');

    }

    function test_an_authenticated_user_may_participate_in_forum_threads(){
        //Given we have an authenticated user
        // $user = factory('App\User')->create();
        // //login in the user
        // //be signs in the user
        // $this->be($user);

        $this->be($user = factory('App\User')->create());

        //And an existing thread
        $thread = create('App\Thread');

        //When the user adds a reply to the thread
        $reply = make("App\Reply");

        // dd($thread->path() . '/replies');
        // $this->postJson($thread->path() .'/replies', $reply->toArray())->json();

        $this->post($thread->path() .'/replies', $reply->toArray());

        //then their reply should be visible on the page

        // $this->get($thread->path())
        //     // ->assertSee($reply->body);
        //     ->assertStatus(200);

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);

    }

    function test_a_reply_requires_a_body(){

        $this->withExceptionHandling()->signIn();

        $thread  = create("App\Thread");

        $reply = make("App\Reply", ['body' => null]);

        // $this->post($thread->path() .'/replies', $reply->toArray())
        //       ->assertSessionHasErrors("body");

        $this->json('POST', $thread->path() .'/replies', $reply->toArray())->assertStatus(422);


    }

    function test_unauthorized_users_cannot_delete_reply(){

        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        //this will throw a 403 error when u try to delete a reply u didnt create
        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    function test_authorized_users_can_delete_a_reply(){

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

            $this->delete("/replies/{$reply->id}")->assertStatus(302);
            $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

            $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    function test_authorized_users_can_update_replies(){

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'you have been changed, fool.';

        $this->patch("/replies/{$reply->id}", [
            'body' => $updatedReply
            ]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedReply
            
        ]);
        
    }

    function test_unauthorized_users_cannot_update_reply(){

        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $updatedReply = 'you have been changed, fool.';

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        //this will throw a 403 error when u try to delete a reply u didnt create
        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    function test_replies_that_contain_spam_may_not_be_created(){
        
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Yahoo customer support'
        ]);

        // $this->expectException(\Exception::class);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }

    function test_users_may_only_reply_a_maximum_of_once_per_minute(){

        $this->withExceptionHandling();

        $this->signIn();
        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'My simple reply'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }
}
