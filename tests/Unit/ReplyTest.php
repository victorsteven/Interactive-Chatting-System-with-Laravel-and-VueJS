<?php

namespace Tests\Unit;
use Carbon\Carbon;
use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{

    use RefreshDatabase;
   
    public function test_it_has_an_owner()
    {
        // $user = factory("App\User")->create();

        // $reply = factory("App\Reply")
        //         ->create(["user_id" => $user->id]);

        // $this->assertSee();

        $reply = factory("App\Reply")->create();

        $this->assertInstanceOf("App\User", $reply->owner);
    }

    function test_it_knows_if_it_was_just_published(){

        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());
        
        //but if the reply was posted a month ago, assert that it was not just posted
        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    function test_it_can_detect_all_mentioned_users_in_the_body(){

        $reply = new \App\Reply([
            'body' => '@janeDoe and @johnDoe are friends'
        ]);

        $this->assertEquals(['janeDoe', 'johnDoe'], $reply->mentionedUsers());
        
    }

    function test_it_wraps_mentioned_usernames_in_the_body_within_anchor_tags(){

        $reply = new \App\Reply([
            'body' => 'Hello @jane.Doe.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/jane.Doe">@jane.Doe</a>.', $reply->body);
    }

    function test_it_knows_if_it_the_best_reply(){
        //we are going to store a best reply id on the thread itself
        //for the reply, it has to reference the Thread relationship and check if its id is the best reply id

        $reply =  create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());


    }
}
