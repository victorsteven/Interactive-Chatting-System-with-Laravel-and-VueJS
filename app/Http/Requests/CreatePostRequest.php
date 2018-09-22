<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\ThrottleException;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return true;
        return Gate::allows('create', new \App\Reply);
    }

    protected function failedAuthorization(){

        throw new ThrottleException('You are replying too frequently please take a brake :)');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'body' => 'required|spamfree'
        ];
    }

    // public function persist($thread){

    //     $reply = $thread->addReply([
    //         'body' => request('body'),
    //         'user_id' => auth()->id(),
    //         ]);

    //         if(request()->expectsJson()){
    //             //remember, we are eager loading the owner and returning it as json
    //             return $reply->load('owner');
    //         }
    // }
}
