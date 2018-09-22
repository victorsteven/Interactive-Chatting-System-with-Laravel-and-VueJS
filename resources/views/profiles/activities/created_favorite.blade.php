@component('profiles.activities.activity')

    @slot('heading')
    <a href="{{ $activity->subject->favorited->path() }}">
    {{ $profileUser->name }} favorited a reply
    {{-- <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->title }}</a>  --}}
    </a>
    @endslot

    @slot('body')
    {{-- activity, give me the model that was favorited, the body of it --}}
    {{ $activity->subject->favorited->body }}

    @endslot

@endcomponent

