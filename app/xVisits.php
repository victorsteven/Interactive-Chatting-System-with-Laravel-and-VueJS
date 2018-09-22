<?php

namespace App;
use Illuminate\Support\Facades\Redis;

class Visits {

    protected $thread;

    public function __construct($thread){

        $this->thread = $thread;
    }
    //note if we are recording visists for blog post, etc, we can make our constructor generic, like:
    // public function __construct($relation){
    //     $this->thread = $relation;
    // }

    public function reset(){

        return Redis::del($this->cacheKey());
    }

    public function count(){

        return Redis::get($this->cacheKey()) ?? 0;

    }

    public function record(){

        Redis::incr($this->cacheKey());

        return $this;
    }

    public function cacheKey(){

        return "threads.{$this->thread->id}.visits";
        
    }
}