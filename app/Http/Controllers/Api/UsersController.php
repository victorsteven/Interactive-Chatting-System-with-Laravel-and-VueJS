<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index(){

        $search = request('name');

        return User::where('name', 'LIKE', "$search%")
                    ->take(5)
                    ->pluck('name');

        //this will return up to 5 users wheather is in the search or not
        // return User::take(5)
        //             ->pluck('name');
    }
}
