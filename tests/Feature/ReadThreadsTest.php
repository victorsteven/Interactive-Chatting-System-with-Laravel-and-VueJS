<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    function setUp(){

        parent::setUp();

        //Global thread creation
        $this->thread =factory('App\Thread')->create();
        
    }
    
    public function test_a_user_view_all_threads()
    {

         $this->get('/threads')
                ->assertSee($this->thread->title);
                // ->assertSee($this->thread->body);
    }


    public function test_a_user_view_a_single_thread()
    {
        //if the user visits a particular thread
        //  $this->get('/threads/' . $this->thread->id)
            $this->get($this->thread->path())
                ->assertSee($this->thread->title);

    }

    // public function test_a_user_can_read_a_reply_associated_with_a_thread(){
    //     //given we have a thread, yes we do in setup
        
    //     //And the thread include replies
    //     $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

    //     //when we visit the thread page
    //     // $this->get('/threads/' . $this->thread->id)
    //         // $this->get($this->thread->path())
    //         // ->assertSee($reply->body);
            
    //         $this->assertDatabaseHas('replies', ['body' => $reply->body]);

    //     //then we should see the replies
    // }

    function test_a_user_can_filter_threads_according_to_a_channel(){

        $channel = create("App\Channel");
        $threadInChannel = create("App\Thread", ['channel_id' => $channel->id]);
        $threadNotInChannel = create("App\Thread");
        
        $this->get("/threads/" . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
   


    function test_a_user_can_filter_threads_by_any_username(){

        // $this->withExceptionHandling();

        $this->signIn(create('App\User', ['name' =>'JohnDoe' ]));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);

        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    function test_a_user_can_filter_threads_by_popularity(){
        //given we have 3 threads
        // with 2replies, 3replies and 0replies
        //when i filter the threads by popularity

        $threadWithTwoReplies = create('App\Thread');

         create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

         $threadWithThreeReplies = create('App\Thread');

         create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

         $threadWithNoReplies = $this->thread;


        $response = $this->getJson('threads?popular=1')->json();

        // dd($response);

        //then they should be returned from most reply to least
        //since we have applied pagination in out controller, this assertion will fail because, the $response will also return the pagination object. so to make it pass, we say we need only the data in the response, not the full response
        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));

    }

    function test_a_user_can_filter_threads_that_are_unanswered(){

        //remember the first thread is created in the setup method. the thread below is the second thread
        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);

    }

    public function test_a_user_can_request_all_reply_for_a_thread(){

        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        // dd($response);

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);

    }

    public function test_we_record_a_new_visit_each_time_the_thread_is_read(){
        $thread = create('App\Thread');

        // dd($thread->visits);
        // dd($thread->fresh()->toArray());
        // dd($thread->toArray());


        $this->assertSame(0, $thread->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }

}
