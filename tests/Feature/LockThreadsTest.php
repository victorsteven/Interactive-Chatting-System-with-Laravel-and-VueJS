<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{

    use RefreshDatabase;

    function test_non_admin_cannot_lock_thread(){

        $this->withExceptionHandling();
        
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    function test_admin_can_lock_threads(){

        // $this->signIn(create('App\User', ['name' => 'JohnDoe']));
        $this->signIn(factory('App\User')->state('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));
        
        $this->assertTrue(!! $thread->fresh()->locked);

    }

    function test_admin_can_unlock_threads(){
        $this->signIn(factory('App\User')->state('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        //if u forget the parenthesis after the "fresh", u will get the error, Trying to get property 'locked' of non-object

        $this->assertFalse(!! $thread->fresh()->locked);

    }
    

    function test_once_locked_a_thread_may_not_receive_new_reply(){

        // $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['locked' => true]);

        // $thread->lock();
        // $thread->update(['locked' => true]);


        //if we try to add a reply to this thread that is locked, it should not work
        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id(),
        ])->assertStatus(422);

    }
}
