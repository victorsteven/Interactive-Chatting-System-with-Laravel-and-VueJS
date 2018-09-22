<?php

namespace App\Inspections;

class InvalidKeywords {

    // public function detectInvalidKeywords($body){

    //     $invalidKeywords = [
    //         'yahoo customer support'
    //     ];

    //     foreach($invalidKeywords as $keywords){

    //         if(stripos($body, $keywords) !== false){
    //             throw new \Exception('your reply contains spam.');
    //         }
    //     }
    // }

    protected $keywords = [
        'yahoo customer support'
    ];
    
    public function detect($body){
      
        foreach($this->keywords as $keyword){

        if(stripos($body, $keyword) !== false){
            throw new \Exception('your reply contains spam.');
        }
    }

}
}