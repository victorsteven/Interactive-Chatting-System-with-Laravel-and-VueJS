@extends('layouts.app')

@section('content')
{{-- THis is the profile of the user, whether he is the auth user or not --}}
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="page-header">
                {{-- note this may not be the signed user, is just the user that have the profile --}}
                <avatar-form :user = "{{ $profileUser }}"></avatar-form>

                {{-- <h1>
                    {{ $profileUser->name }}
                </h1>
                
                @can('update', $profileUser)
        
                 <!--<form action="/api/users/{{ $profileUser->id }}/avatar"> -->
                <form action="{{ route('avatar', $profileUser) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <input type="file" name="avatar" id="">
                
                    <button type="submit" class="btn btn-primary">Add Avatar</button>
                </form>   
        
                @endcan
        
                <img src="{{ asset('storage/' . $profileUser->avatar()) }}" alt="{{ $profileUser->name }}" width="50" height="50"> --}}

            </div>
            
            
            {{-- $date is the big collection, $activity is a collection inside date --}}
            @forelse($activities as $date => $activity)
                <h3 class="page-header">{{ $date }}</h3>
                
                @foreach($activity as $record)
                @if(view()->exists("profiles.activities.{$record->type}"))
                @include("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
                @endforeach
            <p class="mt-4"></p>
            @empty
                <p class="">You dont have any current activity</p>
            @endforelse
            
            <p>
                {{-- {{ $threads->links() }} --}}
            </p>
        </div>
    </div>
    

</div>

@endsection