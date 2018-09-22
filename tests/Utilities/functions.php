<?php

//We are going to create a class give it some attributes but not require it

function create($class, $attributes = [], $times = null){

    return factory($class, $times)->create($attributes);
}


function make($class, $attributes = [], $times = null){

    return factory($class, $times)->make($attributes);
}