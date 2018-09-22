<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread){

        // if(! auth()->user()->isAdmin()){ //if we dont want to do this logic everywhere, we can define a middleware

        //     return response('', 403);
        // }
        
        // $thread->lock();
        $thread->update(['locked' => true]);
    }

    public function destroy(Thread $thread){

        // $thread->unlock();
        $thread->update(['locked' => false]);

    }
}
