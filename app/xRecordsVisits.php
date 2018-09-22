<?php

namespace App;
use Illuminate\Support\Facades\Redis;


trait RecordsVisits {

    public function recordVisits(){
        //we have to use namespacing.

        Redis::incr($this->visitCacheKey());

        return $this;
    }

    public function visits(){

        //if u have anything record it otherwise, default to zero, if we didnt do this, if there was no visit, it would have recorded "null"
        //  return Redis::get($this->visitCacheKey()) ?? 0; //note: what is returned here is an integer

        return new Visits($this);

    }

    public function resetVisits(){

        return Redis::del($this->visitCacheKey()); //clear the redis cache

    }

    public function visitCacheKey(){

        return "threads.{$this->id}.visits";
        
    }
}