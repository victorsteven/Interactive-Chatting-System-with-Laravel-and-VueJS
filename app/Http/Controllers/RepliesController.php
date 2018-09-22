<?php

namespace App\Http\Controllers;

use App\User;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;

class RepliesController extends Controller
{

    public function __construct(){
        
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channel, Thread $thread, CreatePostRequest $form)
    {

        // if(\Gate::denies('create', new Reply)){
        //     return response('You are posting too frequently, please take a brake :)', 422);
        // }
        
        
        //authorize that we can create a new reply
        // $this->authorize('create', new Reply());

        // $this->validate(request(), ['body' => 'required|spamfree']);

        // return $form->persist($thread);

        if($thread->locked){
            return response('Thread is locked', 422);
        }


        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
            ]);
    

        if(request()->expectsJson()){
            //remember, we are eager loading the owner and returning it as json
            return $reply->load('owner');
        }

        // return back()->with('flash', 'Your reply has been left'); //note in ur tests, if u want a redirect this is good, it will give us 302 status, but if we done want redirect, comment this line
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        
        $this->authorize('update', $reply);

        // try {

            request()->validate(['body' => 'required|spamfree']);

            $reply->update(request(['body']));
            
        // }catch(\Exception $e){
        //     return response('Could not update reply at this point', 422);
        // }
    }

    // public function update(Reply $reply)
    // {
    //     $this->authorize('update', $reply);
    //     $this->validate(request(), ['body' => 'required|spamfree']);
    //     $reply->update(request(['body']));
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        // if($reply->user_id != auth()->id()){

        //     return response([], 403);
        // }

        
        
        $this->authorize('update', $reply);
        
        $reply->delete();

        if(request()->wantsJson()){

            return response(['status', 'reply deleted successfully']);

        }

        return back(); //this guy is what makes sure that 302 redirect works, also it is went normal php is used not ajax. because when ajax is used, no need to redirect
    }
}
