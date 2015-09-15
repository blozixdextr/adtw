@extends('app.layouts.client')

@section('content')
    <h1>Decline</h1>

    <p>Twitcher: <a href="/profile/{{ $stream->user_id }}">{{ $stream->user->name }}</a></p>
    <p>Date: {{ $stream->time_start->format('d.m.y H:i') }}</p>

    <p>We are sorry to hear about this. Please leave some comments for streamer</p>
    {!! Form::open(['url' => '/user/client/stream/'.$stream->id.'/'.$banner->id.'/decline', 'class' => 'form-horizontal']) !!}

    <div class="form-group {!! ($errors && $errors->has('comment')) ? ' has-error' : '' !!}">
        {!! Form::label('comment', 'Comment', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::textarea('comment', old('comment'), ['class' => 'form-control', 'placeholder' => 'comment', 'required' => 'required']) !!}
            {!! Form::errorMessage('comment') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-violet btn-default">Save</button>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

