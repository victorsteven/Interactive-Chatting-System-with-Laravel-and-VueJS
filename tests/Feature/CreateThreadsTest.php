<?php

namespace Tests\Feature;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Rules\Recaptcha;

class CreateThreadsTest extends TestCase
{

    use RefreshDatabase;

    function setUp(){
        parent::setUp();

        

        app()->singleton(Recaptcha::class, function(){
            return  \Mockery::mock(Recaptcha::class, function ($m){
                $m->shouldReceive('passes')->andReturn(true);
            });
        });


    }

    function test_guess_may_not_create_threads(){

        $this->withExceptionHandling();

                $this->get('/threads/create')
                ->assertRedirect(route('login'));

                $this->post(route('threads'))
                ->assertRedirect(route('login'));

    }

    function test_new_users_must_first_confirm_their_email_address_before_creating_threads(){

        $user = factory('App\User')->state('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    }

    public function test_a_user_can_create_threads()
    {
        // $this->actingAs(create("App\User"));
        // $this->signIn();

        //when we hit the end point to create a new thread
        //when we post to this array, we need to give it an endpoint not an array, so we use "raw" not "make". But, in our case, since we need to visit a url, we use "make" so that we can get the instance, then manually cast to an array in our endpoint
        // $thread = make("App\Thread");

        // $response = $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $response = $this->publishThread(['title' => 'some title', 'body' => 'some body']);

        // dd($response->headers->get('Location'));
        //then when we visit the page, we should see the thread
        // dd($response->headers->get('Location'));

        $this->get($response->headers->get('Location'))
                ->assertSee('some title')
                ->assertSee('some body');

        // $response->assertRedirect('/threads')->assertSessionHas('flash');

    }

    function test_a_thread_requires_a_title(){

        $this->publishThread(['title' => null])
                ->assertSessionHasErrors('title');
    }

    function test_a_thread_requires_a_body(){

        $this->publishThread(['body' => null])
                ->assertSessionHasErrors('body');
    }

    function test_a_thread_requires_recaptcha_verification(){

        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
                ->assertSessionHasErrors('g-recaptcha-response');
    }

    function test_a_thread_requires_a_valid_channel(){

        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
                ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
                ->assertSessionHasErrors('channel_id');
    }

    public function test_unauthorized_users_may_not_delete_threads(){

        $this->withExceptionHandling();

        $thread = create('App\Thread');
    //    $response =  $this->json('DELETE', $thread->path());
    $this->delete($thread->path())->assertRedirect(route('login'));

    $this->signIn();
    // $this->delete($thread->path())->assertRedirect(route('login'));
    $this->delete($thread->path())->assertStatus(403);

    }

    public function test_authorized_users_can_delete_threads(){

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        // $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);


       $response =  $this->json('DELETE', $thread->path());

    //    $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        // $this->assertDatabaseMissing('activities', [ 
        //     'subject_id' => $thread->id,
        //     'subject_type' => get_class($thread)
        // ]);
        
        // $this->assertDatabaseMissing('activities', [ 
        //     'subject_id' => $reply->id,
        //     'subject_type' => get_class($reply)
        // ]);

        $this->assertEquals(0, Activity::count());

    }



    // public function test_authorized_users_can_delete_threads(){

    //     $this->signIn();
 
    //     $thread = create('App\Thread', ['user_id' =>auth()->id()]);
 
    //     $reply = create('App\Reply', ['thread_id' => $thread->id]);
 
    //     $response = $this->json('DELETE', $thread->path());
 
    //     $response->assertStatus(204);
 
    //     $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    //     $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    // }


    

    // function test_a_thread_require_a_unique_slug(){
        
    //     $this->signIn();

    //     $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);

    //     $this->assertEquals($thread->fresh()->slug, 'foo-title');

    //     //trying to add another thread with the same title
    //     $this->post(route('threads'), $thread->toArray());

    //     $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

    //     //trying to add another thread with the same title
    //     $this->post(route('threads'), $thread->toArray());

    //     $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    // }


    function test_a_thread_require_a_unique_slug(){
        
        $this->signIn();

        // //creating two random threads that may not follow our id convention
        // create('App\Thread', [], 2);

        $thread = create('App\Thread', ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        //trying to add another thread with the same title
        // $this->post(route('threads'), $thread->toArray());
        //using json instead
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'anything'])->json();

        // dd($thread);

        // $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        //This increment now uses the id
        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);

    }


    // function test_a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug(){

    //     $this->signIn();

    //     $thread = create('App\Thread', ['title' => 'Some Title 24']);

    //     $this->post(route('threads'), $thread->toArray());

    //     $this->assertTrue(Thread::whereSlug('some-title-24-2')->exists());

    // }

    //using json for the above:
    function test_a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug(){

        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'anything'])->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);

    }

    public function publishThread($overrides = []){

        $this->withExceptionHandling();

        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

    }

   
}
