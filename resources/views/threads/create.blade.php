@extends('layouts.app')

@section('header')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Thread</div>
                
                <div class="card-body">
                    <form action="/threads" method="POST">
                        @csrf

                        <div class="form-group">
                            <select name="channel_id" id="channel_id" class="form-control">
                                <option value="">Select...</option>
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Body</label>
                            <textarea name="body" id="" rows="8" class="form-control">{{ old('body') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6LdE0mkUAAAAAOB-iFL2gWpuUE6XT8Ct5bFxrrIc"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        

                        @if(count($errors))
                        <ul class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif

                    </form>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
