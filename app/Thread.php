<?php

namespace App;

use App\Visits;
use App\Activity;
use App\Notifications\ThreadWasUpdated;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use App\ThreadSubscription;
// use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;


class Thread extends Model
{


    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = ['locked' => 'boolean'];

    protected static function boot(){
        parent::boot();


        //model event
        static::deleting(function ($thread){
            $thread->replies->each->delete();
            
        });

        static::created(function ($thread){
            $thread->update(['slug' => $thread->title]);
        });

    }

    

    public function path(){

        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function addReply($reply){

        $reply = $this->replies()->create($reply);

        //this event has two listeners
        event(new ThreadReceivedNewReply($reply));

        return $reply;

    }

    

    public function channel(){
        //Every thread need to have a channel_id, which means, in the thread migration, channel_id must be referenced
        return $this->belongsTo(Channel::class);
    }

    //we use the query scope filter, because, this applies to the current reunning thread

    public function scopeFilter($query, ThreadFilters $filters){

        return $filters->apply($query);
    }

    public function subscribe($userId = null){

        $this->subscriptions()->create([
           'user_id' => $userId ?: auth()->id() 
            
        ]);
        //why we need to return this instance is because we can chain it to a call
        return $this; //note, the subscribe method returns thread
    }

    public function subscriptions(){

        return $this->hasMany(ThreadSubscription::class);
    }

    public function unsubscribe($userId = null){

        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    //lets setup a custom eloquent accessor

    public function getIsSubscribedToAttribute(){

        //we dont need to fetch any data, we just want to know if there is a record in there that matches
        return $this->subscriptions()
                ->where('user_id', auth()->id())
                ->exists();
    }

    public function hasUpdatesFor($user = null){
        

        $user = $user ?: auth()->user();

        $key = $user->visitedThreadCacheKey($this); //if you are not signed in, we try to call a method off of null


        //is the updated column more recent than what is in the cache? maybe a new reply is left, if yes, then bold the title of the thread

       return $this->updated_at > cache($key);

       //note we can use a table base approach then use 'hasOne' relationship
    }


public function getRouteKeyName(){

    return 'slug';
}

//note we used the created event at the top to create the slug, when a thread is first created
public function setSlugAttribute($value){

    $slug = str_slug($value);

    //if the slug exists already, we are going tp change it and append the thread id to it
    if(static::whereSlug($slug)->exists()){
        $slug = "{$slug}-" . $this->id;
    }

    // var_dump($slug);

    $this->attributes['slug'] = $slug; //the way we set the slug
}

public function markBestReply(Reply $reply){

    
    $this->best_reply_id = $reply->id;
    $this->save();
    }
   
}
