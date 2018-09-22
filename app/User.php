<?php

namespace App;
use App\Reply;
use App\Thread;
use App\Activity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',  'avatar_path', 'confirmed', 'confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    protected $casts = [
        'confirmed' => 'boolean'
        //note, we can also cast a string to json here
    ];

    public function getRouteKeyName(){

        return 'name';
    }

    public function threads(){
        return $this->hasMany(Thread::class)->latest();
    }

    public function confirm(){

        $this->confirmed = true;
        
        
        $confirmed = $this->save();

        if($confirmed){

            $this->update(['confirmation_token' => null]);

            // dd($this->confirmation_token);
        }

        
    }

    public function isAdmin(){

        return in_array($this->name, ['JohnDoe', 'JaneDoe']);
    }
    

    public function lastReply(){

        //yes a user has many reply, but if we want to fetch a specific one, we use hasOne:
        
        return $this->hasOne(Reply::class)->latest();
        
    }

    public function activity(){
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread){

        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread){

        //when the user visits a thread, we are going to store a new key in the cache and make it equal to the current time
            //which means, the user has visited this thread
            cache()->forever(
                $this->visitedThreadCacheKey($thread),  
                \Carbon\Carbon::now()
            );
    }

    // public function avatar(){

    //     return ($this->avatar_path ?: "images/avatars/default.png");

    //     // if(! $this->avatar_path){

    //     //     return "avatars/default.jpg";
    //     // }
    //     // return $this->avatar_path;

    // }

    public function getAvatarPathAttribute($avatar){

        return ($avatar ? 'storage/' . $avatar : "storage/images/avatars/default.png");
    }

}
