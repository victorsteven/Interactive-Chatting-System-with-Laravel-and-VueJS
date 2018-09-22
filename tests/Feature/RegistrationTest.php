<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;
   
    public function test_a_confirmation_email_is_sent_upon_registration(){

        Mail::fake();

        // event(new Registered(create('App\User')));

        $this->post(route("register"), [
            'name' => "john",
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);


        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    public function test_a_user_can_fully_confirm_their_email_address(){

        Mail::fake(); //THis makes the test runs faster, without it, the test will still work, but it will be slow

        $this->post(route("register"), [
            'name' => "john",
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        $user = User::whereName('john')->first();
        
        // // !! is to cast to boolean
        // $this->assertFalse(!! $user->confirmed);

        //or we can just do that in our user model
        $this->assertFalse($user->confirmed);

        // dd($user->confirmation_token);

        $this->assertNotNull($user->confirmation_token);

        //let the user confirm their account

        //route('foo', ['one' => 'thing']); // /foo?one=thing
        // $this->get("/register/confirm?token={$user->confirmation_token}&ada=good");
        $this->get( route("register.confirm", ['token' => $user->confirmation_token ]))
            ->assertRedirect(route("threads"));

        tap($user->fresh(), function($user){
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    function test_confirming_an_invalid_token(){

        $this->get(route('register.confirm', [
            'token' => 'invalid']
            ))->assertRedirect(route('threads'))
              ->assertSessionHas('flash', 'Unknown token.');
    }
}
