@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/app/css/shared/bootstrap-theme.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/shared/modal.css">
@endsection

@section('content')
    <div class="text-center intro-main">
        <div class="container">
            <h1>Login</h1>
            {!! Form::open(['url' => '/auth/admin', 'class' => 'form-horizontal']) !!}

            <div class="form-group {!! ($errors && $errors->has('login')) ? ' has-error' : '' !!}">
                {!! Form::label('login', 'Login', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">
                    {!! Form::input('email', 'login', old('login'), ['class' => 'form-control', 'placeholder' => 'Login', 'required' => 'required']) !!}
                    {!! Form::errorMessage('login') !!}
                </div>
            </div>

            <div class="form-group {!! ($errors && $errors->has('password')) ? ' has-error' : '' !!}">
                {!! Form::label('password', 'Password', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required']) !!}
                    {!! Form::errorMessage('password') !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9  col-sm-offset-3">
                    <label>{!! Form::checkbox('remember') !!} Remember me</label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-default">Login</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection