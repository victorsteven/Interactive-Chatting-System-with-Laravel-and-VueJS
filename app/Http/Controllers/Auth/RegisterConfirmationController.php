<?php

namespace App\Http\Controllers\Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index(){

        // dd(request()->all()); //this will give use the token, 
        //since we are having the token, we now need to find the user that has that token, when we see him, we update their confirmed column to true

        // User::where('confirmation_token', request('token'))
        //     ->firstOrFail()
        //     ->update(['confirmed' => true]);

        //or
        // $user = User::where('confirmation_token', request('token'))
        // ->firstOrFail();
        // $user->confirmed = true;
        // $user->save();

        // or

        //why we are putting this in try and catch is that, incase we have model not found exception, we can catch it

        // try{
        // User::where('confirmation_token', request('token')) // remember this token is in the query string
        //     ->firstOrFail()
        //     ->confirm();

        // } catch(\Exception $e){
        //     return redirect(route('threads'))
        //         ->with('flash', 'Unknown token.');
        // }

        
        $user = User::where('confirmation_token', request('token')) // remember this token is in the query string
            ->first();

            if(! $user){

                return redirect(route('threads'))
                ->with('flash', 'Unknown token.');
            }

        $confirmed = $user->confirm();

        
        return redirect(route('threads'))
                ->with('flash', 'Your account is now active!'); 
    }
}
