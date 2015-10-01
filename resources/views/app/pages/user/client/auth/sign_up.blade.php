@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-client-auth.css">
@endsection

@section('content')

    {!! Form::open(['url' => '/auth/client/sign-up', 'class' => 'client-auth']) !!}
    <h1>Sign Up as Client</h1>

    <div class="form-group">
        {!! Form::text('name', old('name', ''), ['placeholder' => 'First and last name', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('name') !!}
    </div>

    <div class="form-group">
        {!! Form::input('email', 'email', old('email', ''), ['required' => 'required', 'placeholder' => 'your@email.com', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('email') !!}
    </div>

    <div class="form-group">
        {!! Form::input('password', 'password', old('password', ''), ['required' => 'required', 'placeholder' => 'Password', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('password') !!}
    </div>

    <div class="form-group">
        {!! Form::input('password', 'password2', old('password2', ''), ['required' => 'required', 'placeholder' => 'Confirm password', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('password2') !!}
    </div>

    {!! Form::submit('Register', ['class' => 'btn-white']) !!}<br/> Already have an account? <a href="/auth/client/login">Sign in</a>

    {!! Form::close() !!}

@endsection
