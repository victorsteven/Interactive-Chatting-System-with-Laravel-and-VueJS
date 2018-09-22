<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Trending;

class TrendingThreadsTest extends TestCase
{
   use RefreshDatabase;

   protected function setUp(){

    parent::setUp();

    // Redis::del('testing_trending');


    $this->trending = new Trending();

    $this->trending->reset();


   }

    function test_it_increments_a_thread_score_each_time_it_is_read(){

        // $this->assertEmpty(Redis::zrevrange('testing_trending', 0, -1));
        $this->assertEmpty($this->trending->get());

        $thread = create('App\Thread');

        $this->call('GET', $thread->path());

        // $d = Redis::zrevrange('trending', 0, -1);

        // dd($d);

        // $this->assertCount(1, Redis::zrevrange('trending', 0, -1));

        // $trend = Redis::zrevrange('testing_trending', 0, -1);
        $trend = $this->trending->get();

        $this->assertCount(1, $trend);

        // dd($trend);
        //remember we have already decoded the thread in our show method, no need to do it here again
        $this->assertEquals($thread->title, $trend[0]->title);
        
    }
}
