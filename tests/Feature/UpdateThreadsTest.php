<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{

    use RefreshDatabase;


    //Authorization
    function test_unauthorized_users_may_not_update_thread(){

        $this->withExceptionHandling();

        //sign in a user
        $this->signIn();

        //thread a create by another user
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    //Validation
    function test_a_thread_requires_a_title_and_a_body_to_be_updated(){

        $this->withExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['user_id' =>auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed'
        ])->assertSessionHasErrors('title');
    }

    
    //General Logic
    function test_a_thread_can_be_updated_by_its_creator(){

        $this->signIn();

        $thread = create('App\Thread', ['user_id' =>auth()->id()]);
        // $thread = create('App\Thread', ['user_id' => create('App\User')->id]);


        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body'
        ]);

        tap($thread->fresh(), function($thread){
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed body', $thread->body);
        });
    }
}
