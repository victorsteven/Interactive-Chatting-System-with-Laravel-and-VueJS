{{-- v-cloak adds the attribute until everything has been loaded --}}
<reply :attributes = "{{ $reply }}" inline-template>
<div id="reply-{{ $reply->id }}" class="card"> 
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="/profiles/{{ $reply->owner->name }}">
                    {{ $reply->owner->name }}
                </a> 
                said at  {{ $reply->created_at->diffForHumans() }}...
            </h5>

            @if(Auth::check())
            <div>
                <favorite :reply = "{{ $reply }}"></favorite>
                <!--<form action="/replies/{{ $reply->id }}/favorites" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-default"  
                    {{ $reply->isFavorited() ? "disabled" : "" }}
                    > 
                        {{-- {{ $reply->favorites()->count() }}  instead a query we can reference an attribute--}}
                        {{-- {{ $reply->favorites_count }} --}}
                    </button>
                </form>
            -->
            </div>
            @endif
            
        </div>
    </div>

    
    <div class="card-body">
        <div v-if="editing">
            <div class="form-group">
                <textarea class="form-control" name="" id="" v-model="body"></textarea>
            </div>

            <button class="btn btn-sm btn-primary" @click="update">Update</button>
            <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>

            
        </div>
        <div v-else v-text = "body">
            {{-- {{ $reply->body }} --}}
        </div>
    </div>

    @can('update', $reply)
    <div class="card-footer level">
        <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>

        <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>

        {{-- <form action="/replies/{{ $reply->id }}" method="POST">
            @csrf
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form> --}}
        
    </div>
    @endcan
</div>

</reply>