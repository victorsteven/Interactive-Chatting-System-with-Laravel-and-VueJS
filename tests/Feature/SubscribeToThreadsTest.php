<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    function test_a_user_can_subscribe_to_thread(){

        $this->signIn();

        //given we have a thread...

        $thread = create('App\Thread');

        //and the user subscribes to the thread...
        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
        
    }

    public function test_a_user_can_unsubscribe_from_threads(){

        $this->signIn();

        $thread = create('App\Thread');

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
