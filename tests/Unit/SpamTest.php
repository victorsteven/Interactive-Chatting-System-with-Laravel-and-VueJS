<?php

namespace Tests\Unit;

use App\Inspections\Spam;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    function test_it_checks_include_invalid_keywords(){

        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply'));
        
        $this->expectException(\Exception::class);

        $spam->detect('yahoo customer support');
    }


    function test_it_checks_for_key_held_down(){

        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect('hello aaaaaaaaaaaaaaa');

    }
}
