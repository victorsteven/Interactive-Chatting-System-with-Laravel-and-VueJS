<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;

class BestRepliesController extends Controller
{
    public function store(Reply $reply){

        //can he authorize an update on the thread?
        $this->authorize('update', $reply->thread);
        
        // abort_if($reply->thread->user_id !== auth()->id(), 401);

        // $reply->thread->update([
        //     'best_reply_id' => $reply->id
        // ]);

        $reply->thread->markBestReply($reply);
        
    }
}
