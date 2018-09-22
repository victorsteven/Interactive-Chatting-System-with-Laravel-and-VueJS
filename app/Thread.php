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

    // use RecordsActivity, RecordsVisits;

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = ['locked' => 'boolean'];

    protected static function boot(){
        parent::boot();

        //for all threads apply this particular scope, which is to include the replies count

        //we are now including the "replies_count" column in our threads table, so we dont need to load the replies_count each time the thread loads, rather it is gotten from the database
        // static::addGlobalScope('replyCount', function($builder){
        //     $builder->withCount('replies');
        // });

        //model event
        static::deleting(function ($thread){
            $thread->replies->each->delete();
            // $thread->replies->each(function ($reply){
            //     $reply->delete();
            // });
        });

        static::created(function ($thread){
            $thread->update(['slug' => $thread->title]);
        });

        //when ever a thread is created in the database we also want to create a record in the activities table
        //why it is commented is because, it is now defined in the trait called "RecordsActivity"
        // static::created(function ($thread){
        //     $thread->recordActivity('created');
        // });
    }

    

    public function path(){

        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    // function slugIt(){
    //     return str_slug('')
    // }

    // public function getReplyCountAttribute(){
        
    //     return $this->replies()->count();
    // }

    public function creator(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function addReply($reply){

        // (new \App\Spam)->detect($reply->body);
        
        //note what we are doing here, we are passing an array of data to the create method, so there will be mass asignment error, if u dont declare what to receive in the reply model

        $reply = $this->replies()->create($reply);

        //this event has two listeners
        event(new ThreadReceivedNewReply($reply));

        // $this->notifySubscribers($reply);

        // event(new ThreadHasNewReply($this, $reply));

        // $this->subscriptions->filter(function($sub) use ($reply){
        //     //any of them that returns true in this filter, should proceed to the next call
        //     return $sub->user_id != $reply->user_id;
        // })
        // $this->subscriptions
        //     ->where('user_id', '!=', $reply->user_id)
        //     ->each->notify($reply);

        // ->each(function ($sub) use ($reply){
        //     $sub->notify($reply);
        // });

        // ->each(function($sub) use ($reply){
        //     $sub->user->notify(new ThreadWasUpdated($this, $reply));
        // });
        

        // //prepare notification for the subscribers
        // foreach($this->subscriptions as $subscription){

        //     if($subscription->user_id != $reply->user_id){
        //     //because we used Notifiable trait in our User model, we can call the notify method on user
        //     $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }

        return $reply;

        // $reply = $this->replies()->create($reply);
        // $this->increment('replies_count');
        // return $reply;
    }

    //this is pushed to the listener class
    // public function notifySubscribers($reply){
    //     // $this->subscriptions
    //     //     ->where('user_id', '!=', $reply->user_id)
    //     //     ->each->notify($reply);
    // }

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
        
        //look in the cache for the proper key
        //compare that carbon instance with the $thread->updated_at
        
        //this means a reply has been left since the last time the user visited the page

        //we are using redis here
        // $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);  //e.g users.50.visits.1

        $user = $user ?: auth()->user();

        $key = $user->visitedThreadCacheKey($this); //if you are not signed in, we try to call a method off of null


        //is the updated column more recent than what is in the cache? maybe a new reply is left, if yes, then bold the title of the thread

       return $this->updated_at > cache($key);

       //note we can use a table base approach then use 'hasOne' relationship
    }

//    when using redis
//    public function visits(){
//     return new Visits($this);
//    }

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

// public function setSlugAttribute($value){
//     //note assume we were not in the thread class, we would have use the class name itself, instead of static

//     if(static::whereSlug($slug = str_slug($value))->exists()){
//         $slug = $this->incrementSlug($slug);
//     }
//     //otherwise, if that slug does not exists, we assign it:
//     $this->attributes['slug'] = $slug;
// }

// public function incrementSlug($slug){

//     //Aproach 2
//     //with this aproach, we are performing an n number of queries to get our result
//     $original = $slug;
//     $count = 2;
//     while(static::whereSlug($slug)->exists()){
//         $slug = "{$original}-" . $count++;
//     }
//     return $slug;


//     //aproach 1
//     // $max = static::whereTitle($this->title)->latest('id')->value('slug');

//     // //in php 7, a string can act like an array
//     // //is the last value in the string a number?
//     // if(is_numeric($max[-1])){
//     //     return preg_replace_callback('/(\d+)$/', function($matches){
//     //         return $matches[1] + 1; //replace foo-5 with foo-6
//     //     }, $max);
//     // }
//     // //if is not numeric
//     // return "{$slug}-2"; //ie foo-title second insertion will be foo-title-2
//     }

public function markBestReply(Reply $reply){

    // $this->update([
    //     'best_reply_id' => $reply->id
    // ]);

    //OR
    $this->best_reply_id = $reply->id;
    $this->save();
    }

    // public function lock(){

    //     return $this->update(['locked' => true]);

    // }

    // public function unlock(){

    //     return $this->update(['locked' => false]);
    // }
   
}
