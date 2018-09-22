<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{

    use RefreshDatabase;
    

    public function test_a_channel_conssists_of_threads()
    {
        $channel = create('App\Channel');

        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
