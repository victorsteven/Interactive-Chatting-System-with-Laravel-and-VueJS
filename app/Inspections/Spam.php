<?php

namespace App\Inspections;

use Exception;

use App\Inspections\KeyHeldDown;
use App\Inspections\InvalidKeywords;


class Spam {

    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body){

        // $keywords = new InvalidKeywords;
        // $held = new keyHeldDown;

        // $keywords->detectInvalidKeywords($body);
        // $held->detectKeyHeldDown($body);

        // //if the exception is not thrown, then there is no spam, so we do:
        // return false;

        foreach($this->inspections as $inspection){

            //create a new instance of that or fetch one out of the container

            app($inspection)->detect($body);
        }

        return false;
    }

    
    
   


}