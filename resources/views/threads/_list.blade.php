@forelse($threads as $thread)

    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            {{-- if u are signed in and the thread has updates for you --}}
                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>Posted by:  <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a></h5>
                </div>
                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}  
                </a>
            </div>
        </div>

        <div class="card-body">
            <article>
                <div class="body">
                    {{ $thread->body }}
                </div>
            </article>
            <hr>
        </div>
        {{-- @if($thread->visits()) --}}
        <div class="card-footer">
            {{-- when using Redis --}}
            {{-- {{ $thread->visits()->count() }} visits  --}}
            {{ $thread->visits }} visits
        </div>
        {{-- @endif --}}
    </div>
        <p class="mt-4"></p>
    @empty
        <p>There are no relevant results at this time</p>
@endforelse