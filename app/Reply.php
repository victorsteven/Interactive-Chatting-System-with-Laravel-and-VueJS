<?php

namespace App;
use App\User;
use App\Favorite;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $fillable = ['body', 'user_id', 'thread_id'];

    protected $with = ['owner', 'favorites'];

    //using the appends attribute means, whenever you cast to json or an array, is there any custom attribute you want to append to that

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];
    // protected $appends = ['favoritesCount', 'isFavorited'];


    //override the boot method
    protected static function boot(){
        parent::boot();

        //when i create a new reply, i need to then go to the thread relationship and increment, its replies_count
        static::created(function($reply){
            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply){
            //using the model approach to set "best_reply_id" to null in the thread table when the reply is deleted
            // if($reply->isBest()){
            //     $reply->thread->update(['best_reply_id' => null]);
            // }
            
            $reply->thread->decrement('replies_count');
            // dd($reply->thread->replies_count);

        });
    }


    public function owner(){
        //here we are explicit that the foreign key is called user_id
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread(){
        return $this->belongsTo(Thread::class);
    }

    public function path(){

        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function wasJustPublished(){

        //if the reply u left is less than a minute ago, u are posting too soon
        //the created_at is greater because the "now" was taken one minute back
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers(){
        //'/@([\w\-]+)/' that cannot work for users that have period inside their name, so we the one below that can match that period, but does not match the period after it
         preg_match_all('/@([\w\.\-]+)(?<!\.)/', $this->body, $matches);

         return $matches[1];
    }

    //we will use mutator

    public function setBodyAttribute($body){
        //assign the body attribute from what we get in our regular expression

        //$1 means we are matching the second item in the matched array
        //$0 means give me the entire match, including the @ symbol
        // '/@([\w\-]+)/' this is the old guy, 
        //the old one reads match as many as u can, provided that what comes after is not a period(this: (?<!\.) ensures that) 
        // what it means is "jane.Doe" will pass, "jane-Doe" will pass, "janeDoe" will pass, "janeDoe." the match will remove the dot
        $this->attributes['body'] = preg_replace('/@([\w\.\-]+)(?<!\.)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest(){
        // if(! $this->thread->user_id !== auth()->id()) return;
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute(){

        return $this->isBest();
    }
    
}
