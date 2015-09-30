@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-client-auth.css">
@endsection

@section('content')

    {!! Form::open(['url' => '/auth/client/login', 'class' => 'client-auth']) !!}
    <h1>Sign In as Client</h1>

    <div class="form-group">
        {!! Form::input('email', 'email', old('email', ''), ['required' => 'required', 'placeholder' => 'your@email.com', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('email') !!}
    </div>

    <div class="form-group">
        {!! Form::input('password', 'password', old('password', ''), ['required' => 'required', 'placeholder' => 'Password', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('password') !!}
    </div>

    {!! Form::submit('Sign In', ['class' => 'btn']) !!} or <a href="/auth/client/sign-up">Register</a>
    <br><a href="/auth/client/reset">Forgot password?</a>

    {!! Form::close() !!}

@endsection
