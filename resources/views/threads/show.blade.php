@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">

    <!--
    <script>
        window.thread = <?= json_encode($thread); ?>
    </script>
-->

@endsection

@section('content')

{{-- <thread-view :data-replies-count ="{{ $thread->replies_count }}" :data-locked="{{ $thread->locked }}" inline-template> --}}
<thread-view :thread ="{{ $thread }}" inline-template>


<div class="container">
    <div class="row">
        <div class="col-md-8">
                
                @include('threads._question')

                <replies
                    @added="repliesCount++"
                    @removed="repliesCount--"
                    >
                </replies>
                
                {{-- @foreach($replies as $reply)
                    @include('threads.reply')
                    <p class="mt-4"></p>
                    
                @endforeach


                <div class="mt-4"></div>
                
                <p class="px-4">
                    {{ $replies->links() }}   
                </p> --}}


                {{-- @if(Auth::check())
                    <div class="card p-2">
                        <form action="{{ $thread->path() . '/replies'}}" method="POST">
                            @csrf
                            <textarea name="body" id="" class="form-control mt-2" placeholder="Add reply..">
                            </textarea>

                            <button type="submit" class="btn btn-primary mt-4 mb-2 ml-2">Add Reply</button>
                        </form>
                    </div>
                @else 

                <p class="text-center mt-4">Please <a href="{{ route('login') }}">sign in</a>  to participate in this discussion</p>
                @endif --}}

        </div>

        <div class="col-md-4">
            <div class="card">
            
                <div class="card-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by  <a href="">{{ $thread->creator->name }}</a> 
                        {{-- and currently has {{ $thread->replies()->count() }} comments --}}
                        and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count ) }}

                    </p>

                    <p>
                        {{-- note:  $thread->isSubscribeTo returns a boolean, so we use "true" : "false" on it --}}
                        {{-- or best we get the string version by using json_encode --}}
                        {{-- <subscribe-button :active = {{ $thread->isSubscribeTo ? 'true' : 'false' }}></subscribe-button> --}}
                        <subscribe-button :active = "{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>


                        <button class="btn btn-default" 
                            v-if="authorize('isAdmin')" 
                            @click="toggleLock" 
                            v-text="locked ? 'Locked' : 'Lock'"
                            >
                        </button>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</thread-view>

@endsection
