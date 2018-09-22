<?php

namespace Tests\Feature;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    
     public function test_only_members_can_add_avatar(){
        //note this guy is strictly if u want to get code figures like 200, 401, 404, 500.
        //but if u want to get real database error, for instance, a controller is not found, etc comment it.
        $this->withExceptionHandling(); 

        //to get the proper response for status codes, u must turn on exception handling
        $this->json('POST', '/api/users/1/avatar')
                ->assertStatus(401);
     }

     function test_a_valid_avatar_must_be_provided(){

        $this->withExceptionHandling();
        $this->signIn(); 

        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
     }
     

     function test_a_user_may_add_an_avatar_to_their_profile(){

        $this->signIn();

        Storage::fake('public');

        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);
        
        $this->assertEquals('storage/avatars/' . $file->hashName(), auth()->user()->avatar_path);

        // dd(auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
     }

    
}
