<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersAvatarController extends Controller
{


    public function store(){

        $this->validate(request(), [
            'avatar' => 'required|image'
        ]);
        
        // in the public directory inside the avatars folder
        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);


        return response([], 204);

        // return back();  //note, if we do an ajax request, we might need to change this
    }

}
