<?php

namespace App\Filters;

use App\User;
use App\Thread;
use Illuminate\Http\Request;


class ThreadFilters extends Filters {

    protected $filters = ['by', 'popular', 'unanswered'];

    public function by($username){

        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);

    }

    public function popular(){
        //getQuery means get the query instance
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered(){

        return $this->builder->where('replies_count', 0);
    }
}