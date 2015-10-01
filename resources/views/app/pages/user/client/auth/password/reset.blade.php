@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-client-auth.css">
@endsection

@section('content')

    {!! Form::open(['url' => '/auth/client/password/reset', 'class' => 'client-auth']) !!}
    <h1>Set New Password</h1>

    {!! Form::hidden('token', $token) !!}

    <div class="form-group">
        {!! Form::input('email', 'email', old('email'), ['required' => 'required', 'placeholder' => 'your@email.com', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('email') !!}
    </div>

    <div class="form-group">
        {!! Form::input('password', 'password', old('password'), ['required' => 'required', 'placeholder' => 'New password', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('password') !!}
    </div>

    <div class="form-group">
        {!! Form::input('password', 'password_confirmation', old('password_confirmation'), ['required' => 'required', 'placeholder' => 'Confirm new password', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('password_confirmation') !!}
    </div>

    {!! Form::submit('Reset Password', ['class' => 'btn-white']) !!}

    {!! Form::close() !!}

@endsection
