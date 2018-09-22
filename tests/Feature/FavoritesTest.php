<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{

    use RefreshDatabase;

    public function test_guests_may_not_favorite_anything(){

    $this->withExceptionHandling()
        ->post("replies/1/favorites")
        ->assertRedirect('/login');

    }
    
   public function test_an_authenticated_user_can_favorite_any_reply(){

    $this->signIn();

    $reply = create('App\Reply'); // This guy also creates a thread in the process,

    $this->post("replies/{$reply->id}/favorites");

    // dd(\App\Favorite::all());

    //it should be recorded in the database
    $this->assertCount(1, $reply->favorites);
   }

   public function test_an_authenticated_user_can_unfavorite_a_reply(){

    $this->signIn();

    $reply = create('App\Reply'); // This guy also creates a thread in the process,


    // $this->post("replies/{$reply->id}/favorites");
    // $this->assertCount(1, $reply->favorites);    
    // $this->delete("replies/{$reply->id}/favorites");
    //because we have eager loaded the favorites, we need to get a fresh instance
    // $this->assertCount(0, $reply->fresh()->favorites);

            // OR:

    $reply->favorite();
    
    $this->delete("replies/{$reply->id}/favorites");
    $this->assertCount(0, $reply->favorites);

   }

   public function test_an_authenticated_user_may_favorite_a_reply_once(){

    $this->signIn();

    $reply = create('App\Reply'); 

    try{
        $this->post("replies/{$reply->id}/favorites");
        $this->post("replies/{$reply->id}/favorites");
    }catch(\Exception $e){
        $this->fail('Did not expect to insert the same record twice');
    }

    

    // dd(\App\Favorite::all()->toArray());

    $this->assertCount(1, $reply->favorites);
   }
}
