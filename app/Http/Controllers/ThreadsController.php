<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Thread;
use App\Channel;
use App\Filters\ThreadFilters;
use Illuminate\Http\Request;
// use App\Inspections\Spam;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{

    public function __construct(){
        $this->middleware("auth")->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()){
            return $threads;
        }

        //it will give us encoded values, so we map over them and decode, for mapping to work, we need to collect it first wrapping in "collect()"
        // $trending = collect(Redis::zrevrange('trending', 0, 4))->map(function($thread){
        //     return json_decode($thread);
        // });

        //OR
        // $trending = array_map('json_decode', Redis::zrevrange('trending', 0, 4));

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {
        // dd(auth()->user());

        // dd(request()->all());
        
        //validate
        $this->validate($request, [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            //yes the channel_id is required but it needs to exists on the channels table, look on the id column
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha]

        ]);

        

        // dd($response->json());

        // dd("here");

        // $spam->detect(request('title'));
        // $spam->detect(request('body'));


        //create
        $thread = Thread::create([
            "user_id" => auth()->id(),
            "channel_id" => request("channel_id"),
            "title" => request("title"),
            "body" => request("body"),
            // "slug" => request("title")
        ]);

        if(Request()->wantsJson()){
            return response($thread, 201);// you tell your json what to return for you. in our case, we are returning the $thread we posted
            // or you can return a status code:
            // return response(['status', 201]);
            // return response(['status', 'success']);


        }

        //redirect
        return redirect($thread->path())
                ->with('flash', 'Your thread has been published');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        // return $thread;
        // dd($thread->isSubscribedTo);
        // return $thread->load('replies');
        // return $thread->replyCount;

        //eager the reply also the favorites associated with it, also the owner of those replies
        // return $thread->load('replies.favorites')->load('replies.owner');

        // return $thread->replies;

        // return view("threads.show", [
        //     'thread' => $thread,
        //     'replies' => $thread->replies()->paginate(25)
        // ]);

        //record that the user visited this page.

        //record the timestamp
        
        if(auth()->check()){
            auth()->user()->read($thread);
        }

       $trending->push($thread);

       $thread->increment('visits');

    //    $thread->visits()->record(); // why this guy is here is because, it is when u click to show a thread thats when visit actually occurs, not when u just see all the threads in the index. it is the visits method that is called in the index, to display what "recordVisits" records


        return view("threads.show", compact('thread'));
    }

/**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {

        //authorization
        $this->authorize('update', $thread);

        //validation
        // $data = request()->validate([
        //     'title' => 'required|spamfree',
        //     'body' => 'required|spamfree'
        // ]);
        // //update
        // // $thread->update(request(['title', 'body']));
        // $thread->update($data);

        //this will return a boolean 
        // return $thread->update(request()->validate([
        //     'title' => 'required|spamfree',
        //     'body' => 'required|spamfree'
        // ]));
        //to make it return an object or a string, we do:
        // return tap($thread)->update(request()->validate([
        //     'title' => 'required|spamfree',
        //     'body' => 'required|spamfree'
        // ]));
        //Or we can update the thread and return it:
        $thread->update(request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree'
        ]));
        return $thread;

        if(request()->wantsJson()){
            return response([], 204);

        }



        //we are just updating a portion of the thread
        // if(request()->has('locked')){

        //     if(! auth()->user()->isAdmin()){

        //         return response('', 403);
        //     }
        //     // $thread->update(['locked' => true]);
        //     $thread->lock();
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // dd($thread);

        $this->authorize('update', $thread);
        
        // if($thread->user_id != auth()->id()){
        //     abort(403, 'you do not have permission to do this');
        //     // if(request()->wantsJson()){
        //     //     return response(['status' => 'permission denied'], 403);
        //     // }
        //     // return redirect('/login');
        // }

        $thread->delete();

        if(request()->wantsJson()){
            return response([], 204);

        }


        return redirect()->route('threads');
    }

    // protected function getThreads(Channel $channel){

    //     if($channel->exists){
    //         $threads = $channel->threads()->latest();
    //     }else {
    //         $threads = Thread::latest();
    //     }
    //     //if request('by'), we should filter by the given username
        

    //     $threads = $threads->get();

    //     return $threads;
    // }

    protected function getThreads(Channel $channel, ThreadFilters $filters){

        $threads = Thread::latest()->filter($filters);

        if($channel->exists){
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());
        
        $threads = $threads->paginate(25);

        return $threads;
    }
}
