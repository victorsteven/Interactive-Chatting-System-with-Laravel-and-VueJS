<?php

namespace App;

trait RecordsActivity {

    //the convention is use "boot" followed by the trait name,
    // protected static function bootRecordsActivity(){
    //      static::created(function ($thread){
    //         $thread->recordActivity('created');
    //     });
    // }

    //note if we dont want to record  only "created" the above function can be reformatted as: 
    protected static function bootRecordsActivity(){
        if(auth()->guest()) return; //if we dont have an authenticated user, do nothing
        foreach(static::getActivitiesToRecord() as $event){
            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });
        }
        
        static::deleting(function ($model){
            $model->activity()->delete();
        });
    }
    
    protected static function getActivitiesToRecord(){

        return ['created'];
    }
    

    protected function recordActivity($event){

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);

        

        // Activity::create([
        //     'user_id' => auth()->id(),
        //     // 'type' => 'created_thread',
        //     //to get thread dynamically
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     // 'subject_type' => 'App\Thread'
        //     'subject_type' => get_class($this)

        // ]);
    }

    public function activity(){

        //when we use morphMany, we are not hard coding the related model
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event){

        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
        // return $event . '_' . $type;
    }
}