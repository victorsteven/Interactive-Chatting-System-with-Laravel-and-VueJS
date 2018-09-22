<?php

namespace App\Rules;

use Zttp\Zttp;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
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
        // dd('hello!');
        $response = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            // 'response' => $request->input('g-recaptcha-response'),
            'response' => $value,
            // 'remoteip' => $_SERVER['REMOTE_ADDR']
            'remoteip' => request()->ip()
        ]);

        // if(! $response->json()['success']){
        //     throw new \Exception('Recaptcha failed');
        // }
        //dont throw an exception, return a boolean
        //if the response is set to true in the json, we are good to go
        return $response->json()['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Recaptcha verification failed. Try again';
    }
}
