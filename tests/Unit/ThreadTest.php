<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\ThreadWasUpdated;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    function setUp(){

        parent::setUp();

        $this->thread = factory("App\Thread")->create();

    }

    function test_a_thread_has_a_path(){

        $thread = create('App\Thread');
        // dd($thread->path());

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
        // $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    function test_a_thread_has_a_creator(){

        //given we have a thread

        //when we get the creator, it show be an instance of USer
        $this->assertInstanceOf("App\User", $this->thread->creator);

    }

    public function test_a_thread_has_replies()
    {

    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    

    function test_a_thread_can_add_a_reply(){
        $this->thread->addReply([
            'body' => "Foobar",
            'user_id' => 1
        ]);

    $this->assertCount(1, $this->thread->replies);

    }

    function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added(){

        \Notification::fake();

        // $this->signIn();
        // $this->thread->subscribe();

        $this->signIn()->thread->subscribe()->addReply([
            'body' => 'Foobar',
            // 'user_id' => 1     //if we sign in and the sign-in user have the same id as this, it wont work, here, the signIn user id is 2, so if 2 is used it will fail, we can use random number outside 1 and the test will still pass
            //or we can just create a new user
            'user_id' => create('App\User')->id,
        ]);

        \Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    public function test_a_thread_has_a_channel(){
        $thread = create("App\Thread");

        $this->assertInstanceOf("App\Channel", $thread->channel);
    }

    function test_a_thread_can_be_subscribed_to(){

        //given we have a thread
        $thread = create('App\Thread');

        //and an authenticated user
        // $this->signIn();

        //when a user subscribe for a thread, 
        $thread->subscribe($userId = 1);
        
        //it should be represented in the database
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    function test_a_thread_can_be_unsubscribed_from(){

        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    function test_it_knows_if_the_authenticated_user_is_subscribed_to_it(){

        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);


        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    function test_a_thread_can_check_if_the_authenticated_user_has_read_all_replies(){
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function($user) use($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);
    
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
       
    }

    //THIS IS WHEN WE USED REDIS, USE THIS ONLY WHEN YOU EXPECT A HUGE TRAFFIC ON YOUR SITE, 
    //For small traffic we rather create a database column on threads table, then write a new test on ReadThreadsTest

    // function test_a_thread_records_each_visits(){

    //     //we dont need to save this thread in the database, but we need the id

    //     $thread = make('App\Thread', ['id' => 1]);

    //     $thread->visits()->reset();

    //     // dd($thread->visits()->count());

    //     $this->assertSame(0, $thread->visits()->count());

    //     // $thread->resetVisits();
    //     // $this->assertSame(0, $thread->visits());


    //     $thread->visits()->record(); //incr 100 to 101
    //     $this->assertEquals(1, $thread->visits()->count());

    //     // $thread->recordVisits(); //incr 100 to 101
    //     // $this->assertEquals(1, $thread->visits());

    //     $thread->visits()->record(); //incr 101 to 102
    //     $this->assertEquals(2, $thread->visits()->count());

    // }
    

    // function test_a_thread_can_be_locked(){

    //     //thread is also created in setup method
    //     $this->assertFalse($this->thread->locked);

    //     $this->thread->lock();

    //     $this->assertTrue(!! $this->thread->fresh()->locked);

    // }

    // function test_a_thread_can_be_unlocked(){

    //     $this->thread->lock();

    //     $this->assertTrue(!! $this->thread->locked);

    //     $this->thread->unlock();

    //     $this->assertFalse(!! $this->thread->fresh()->locked);
    // }
}
