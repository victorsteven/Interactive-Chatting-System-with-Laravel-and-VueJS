<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    function test_mentioned_users_in_a_reply_are_notified(){

        //given a user is signed in 
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        //and another user @janeDoe
        $jane = create('App\User', ['name' => 'JaneDoe']);

        //if we have a thread
        $thread = create('App\Thread');

        //And @johnDoe replies and mention @janeDoe
        $reply = make('App\Reply', [
            'body' => '@JaneDoe, look at this, also @FrankDoe @nobody'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());


        //then @JaneDoe should be notified
        $this->assertCount(1, $jane->notifications);

    }

    function test_it_can_fetch_all_mentioned_users_starting_with_the_given_character(){

        create('App\User', ['name' => 'johnDoe']);
        create('App\User', ['name' => 'johnDoe2']);
        create('App\User', ['name' => 'janeDoe']);


        //syntax for json request
        // public function json($method, $uri, array $data = [], array $headers = [])

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());

        


    }
}
