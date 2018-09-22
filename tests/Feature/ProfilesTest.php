<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Profiles extends TestCase
{

    use RefreshDatabase;
    
    public function test_a_user_has_a_profile(){

        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    public function test_profiles_display_all_threads_created_by_associated_user(){

        $this->signIn();

        // $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get("/profiles/" . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
