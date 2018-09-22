{{-- Editing --}}

<div class="card" v-if="editing" v-cloak>
<div class="card-header">
    <div class="level">
        <span class="flex">
        
        <input type="text" name="title" id=""  class="form-control" v-model ="form.title">
        </span>
    </div>
</div>
<div class="card-body">
    {{-- {{ $thread->body }} --}}
    <textarea name="body" id=""  class="form-control" rows="8" v-model="form.body"></textarea>
</div>

<div class="card-footer">
    <div class="level">
    <button class = "btn btn-sm btn-default" @click="editing = true" v-show="! editing">Edit</button>
    <button class = "btn btn-sm btn-primary" @click="updateThread">Update</button>
    <button class = "btn btn-sm btn-link mr-2" @click="resetForm">Cancel</button>

    <span class="ml-auto">
    @can('update', $thread)
    <form action="{{ $thread->path() }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <button class="btn btn-link" type="submit">Delete Thread</button>
    </form>
    @endcan
</span>
</div>
</div>
</div>



<div class="card" v-else>
<div class="card-header">
    <div class="level">
        <span class="flex">
        <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">

        <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> 
            posted:   <span v-text="title"></span>
        </span>

        {{-- @can('update', $thread)
        <form action="{{ $thread->path() }}" method="POST">
            @csrf
            {{ method_field('DELETE') }}
            <button class="btn btn-link" type="submit">Delete Thread</button>
        </form>
        @endcan --}}
    </div>
    
</div>
<div class="card-body" v-text="body"></div>

<div class="card-footer" v-show="authorize('owns', thread)">
    <button class = "btn btn-sm btn-default" @click="editing = true">Edit</button>
</div>
</div>
