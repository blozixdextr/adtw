@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-contact-us.css">
@endsection

@section('content')

    {!! Form::open(['url' => '/contact-us', 'id' => 'contactUs']) !!}
    <h1>Contact Us</h1>

    <div class="form-group">
        {!! Form::label('title', 'Your Name') !!}
        {!! Form::text('title', old('title', $username), ['required' => 'required', 'placeholder' => 'Your Name', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('title') !!}
    </div>

    <div class="form-group">
        {!! Form::label('message', 'Your Message') !!}
        {!! Form::textarea('message', old('message', ''), ['required' => 'required', 'placeholder' => 'Your Message', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('message') !!}
    </div>


    {!! Form::submit('Send', ['class' => 'btn-white']) !!}

    {!! Form::close() !!}

@endsection
