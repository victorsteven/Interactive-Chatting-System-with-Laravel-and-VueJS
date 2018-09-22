<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    
    public function show(User $user){

        // return $activities;

        return view('profiles.show', [
            'profileUser' => $user, //note, the user is the one u are viewing his profile, not the authenticated user
            // 'threads' => $user->activity()->paginate(30)
            // 'activities' =>  $this->getActivity($user)
            'activities' =>  \App\Activity::feed($user)

        ]);
    }

    // public function getActivity(User $user){
        
    // }
}
