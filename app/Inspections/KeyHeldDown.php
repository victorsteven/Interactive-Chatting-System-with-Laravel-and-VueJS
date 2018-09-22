<?php

namespace App\Inspections;

class KeyHeldDown {


    // public function detectKeyHeldDown($body){

    //     //refer back to the first match u have using "\1"
    //     //check if the match occur at least 4 times, start matching from the fifth guy
        
    //    if(preg_match('/(.)\\1{4,}/', $body)) {

    //     throw new \Exception('your reply contains spam.');

    //     }

    // }

    public function detect($body){
        if(preg_match('/(.)\\1{4,}/', $body)) {

        throw new \Exception('your reply contains spam.');
    
        }
    }
}
