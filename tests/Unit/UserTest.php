<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use  RefreshDatabase;

   public function test_a_user_can_fetch_their_most_recent_reply(){

    $user = create('App\User');

    $reply = create('App\Reply', ['user_id' =>  $user->id]);

    $this->assertEquals($reply->id, $user->lastReply->id);
   }

   function test_a_user_can_determine_their_avatar_path(){

    $user = create('App\User');

    //if u dont have avatar path, we give u default
    $this->assertEquals('storage/images/avatars/default.png', $user->avatar_path);

     $user->avatar_path = 'images/avatars/me.jpg';

    $this->assertEquals('storage/images/avatars/me.jpg', $user->avatar_path);
   }
}
