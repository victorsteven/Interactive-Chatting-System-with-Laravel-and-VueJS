<?php

namespace Tests\Unit;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_it_records_activity_when_a_thread_is_created(){

        $this->signIn();

       $thread = create('App\Thread');
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = \App\Activity::first();

        //when you create a thread, an activity is also created. the subject id should match with the id of the thread just created
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_it_activity_when_a_reply_is_created(){

        $this->signIn();

        //remember when a reply is created, because of the relationship we have in our model factory, a thread is also created, so we will have two activities
        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }

    function test_it_fetches_a_feed_for_any_user(){

        $this->signIn();

        //we have a current thread by the user
        create('App\Thread', ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        //then we fetch their feed
        $feed = Activity::feed(auth()->user());

        //becuase we are sorting by date, when we fetch the feed of this two threads created, they should be separated into two different items
        // dd($feed->toArray());
        //then it should be returned in the proper format

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
