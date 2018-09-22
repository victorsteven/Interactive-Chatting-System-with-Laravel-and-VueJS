<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Inspections\Spam;

class SpamFree implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try{
        //we are going to return that it passes just as long as it does not detect any spam
        return ! resolve(Spam::class)->detect($value);

        }catch(\Exception $e){

            return false;
        }
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
