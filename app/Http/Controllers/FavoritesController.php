<?php

namespace App\Http\Controllers;
use App\Reply;
use DB;
use App\Favorite;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    
    public function store(Reply $reply){

         //favorites will be a polymorphic relationship, because a user may favorite a reply, a thread, etc

        //  DB::table('favorites')->insert([
        //  Favorite::create([
        //     'user_id' => auth()->id(),
        //     'favorited_id' => $reply->id,
        //     'favorited_type' => get_class($reply)
        // ]);
        

        //we dont need to pass the 'favorited_id' and 'favorited_type'. they are already passed for us
        //eloquent sets the id and the class for the reply, we just to provide the user_id
        // $reply->favorites()->create(['user_id' => auth()->id()]);

        $reply->favorite();

        return response(['status', 'favorited successfully']);


        return back();
    }

    public function destroy(Reply $reply){
        
        $reply->unfavorite();

        return response(['status', 'unfavorited successfully']);
    }
}
