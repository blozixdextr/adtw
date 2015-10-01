@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-client-auth.css">
@endsection

@section('content')

    {!! Form::open(['url' => '/auth/client/password/email', 'class' => 'client-auth']) !!}
    <h1>Restore your password</h1>

    <div class="form-group">
        {!! Form::input('email', 'email', old('email'), ['required' => 'required', 'placeholder' => 'your@email.com', 'class' => 'form-control']) !!}
        {!! Form::errorMessage('email') !!}
    </div>

    {!! Form::submit('Send Password Reset Link', ['class' => 'btn-white']) !!}

    {!! Form::close() !!}

@endsection
