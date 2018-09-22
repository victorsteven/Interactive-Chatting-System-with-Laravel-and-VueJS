<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
   use RefreshDatabase;

   function test_a_thread_creator_may_mark_any_reply_as_best(){

        $this->signIn();

       $thread = create('App\Thread', ['user_id' => auth()->id()]);

       $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

       $this->assertFalse($replies[1]->isBest());

       $this->postJson(route('best-replies.store', [$replies[1]->id]));

       $this->assertTrue($replies[1]->fresh()->isBest());
   }

   function test_only_the_thread_creator_may_mark_a_reply_as_best(){

    $this->withExceptionHandling();

    $this->signIn();

    $thread = create('App\Thread', ['user_id' => auth()->id()]);
    
    $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

    $this->signIn(create('App\User'));

    $this->postJson(route('best-replies.store', $replies[1]->id))->assertStatus(403);

    $this->assertFalse($replies[1]->fresh()->isBest());
    

   }

   function test_if_the_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reply_that(){

    $this->signIn();
    //wiping up a reply creates a thread too

    $reply = create('App\Reply', ['user_id' => auth()->id()]);

    $reply->thread->markBestReply($reply);

    $this->deleteJson(route('replies.destroy', $reply));   //we can also do $reply->id, but eloquent is smart to get the id for us
    $this->assertNull($reply->thread->fresh()->best_reply_id);

   }
}
