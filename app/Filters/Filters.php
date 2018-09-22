<?php

namespace App\Filters;
use App\User;
use App\Thread;
use Illuminate\Http\Request;


//abstract means, we dont intend to instantiate this class, rather a sub-class will be instantiated

abstract class Filters {

    protected $builder;
    protected $request;

    protected $filters = [];


    public function __construct(Request $request){
        $this->request = $request;
    }

    public function apply($builder){

        $this->builder = $builder;

        // dd($this->getFilters());
        // dd($this->request->only($this->filters));

        // //using the functional approach
        // collect($this->getFilters())
        //         ->filter(function($value, $filter){
        //             return method_exists($this, $filter);
        //         })
        //         ->each(function ($value, $filter){
        //             $this->$filter($value);
        //         });
        
        
        foreach($this->getFilters() as $filter => $value){
            if(method_exists($this, $filter)){
                 $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function getFilters(){
        return $this->request->only($this->filters);
    }
}