<?php

namespace App;


trait Favoritable {

    protected static function bootFavoritable(){

        static::deleting(function ($model){
            $model->favorites->each->delete();
        });
    }


    public function favorites(){
        //the name convention we use is "favorited"
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite(){
        //if we didnt find any record in the database, only in that case should be create a new one
        $attributes = ['user_id' => auth()->id()];

        if(! $this->favorites()->where($attributes)->exists()){
            return $this->favorites()->create($attributes);
        }
    }

    public function isFavorited(){

        //we dont want to use sql query as below, will have already called it with replies above
        // return  ! $this->favorites->where('user_id',  auth()->id())->isEmpty();

        //so we do: 
        //we are casting to boolean "!!"
        //if there is a reply, the user favorites the reply
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    //we dont want to hard-code "isFavorited" in our vue file, so we will do is to get its attribute, so that we can do something like: $reply->isFavorited, as seen below:

    public function getIsFavoritedAttribute(){

        return $this->isFavorited();
    }


    public function getFavoritesCountAttribute(){

        return $this->favorites->count();
    }

    public function unfavorite(){
        
        $attributes = ['user_id' => auth()->id()];

        //remember we are in the reply instance, so we do:

        //delete only the one favorited was done by the authenticated user

        // $this->favorites()->where($attributes)->delete(); //This is just an sql query without any instance of Favorite model
        //rather than calling delete to perform the sql query, instead, we get a collection of those models and filter over them 
        // $this->favorites()->where($attributes)->get()->each(function ($favorite){
        //     $favorite->delete(); //here we are deleting the model not just performing an sql query
        // });
        //using higher order collection syntax for the above:
        $this->favorites()->where($attributes)->get()->each->delete();


    }
}