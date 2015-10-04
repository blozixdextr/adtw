@extends('app.layouts.client')

@section('content')
    
    <div class="panel panel-deafult">
    <h1 class="panel-heading"><i class="fa fa-key"></i> Change Password</h1>
    <div class="panel-body">
    {!! Form::open(['url' => '/user/client/profile/password', 'class' => 'form-horizontal profile-table']) !!}
    <div class="fields-border">
    <div class="form-group-1 width-50 {!! ($errors && $errors->has('password')) ? ' has-error' : '' !!}">
        {!! Form::label('password', 'Current Password', ['class' => 'control-label']) !!}
        <div>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Current Password', 'required' => 'required']) !!}
            {!! Form::errorMessage('password') !!}
        </div>
    </div>

    <div class="form-group-1 width-50 {!! ($errors && $errors->has('new_password')) ? ' has-error' : '' !!}">
        {!! Form::label('new_password', 'New Password', ['class' => 'control-label']) !!}
        <div>
            {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'New Password', 'required' => 'required']) !!}
            {!! Form::errorMessage('new_password') !!}
        </div>
    </div>

    <div class="form-group-1 width-50 {!! ($errors && $errors->has('new_password2')) ? ' has-error' : '' !!}">
        {!! Form::label('new_password2', 'Confirm New Password', ['class' => 'control-label']) !!}
        <div>
            {!! Form::password('new_password2', ['class' => 'form-control', 'placeholder' => 'Confirm New Password', 'required' => 'required']) !!}
            {!! Form::errorMessage('new_password2') !!}
        </div>
    </div>

    <div class="form-group-1 width-50 text-center">
        <div>
            <button type="submit" class="btn-white">Save</button>
        </div>
    </div>
    </div>
    {!! Form::close() !!}
    </div>
    </div>

    <h1><i class="fa fa-user"></i> Profile</h1>
    {!! Form::open(['url' => '/user/client/profile/save', 'class' => 'form-horizontal profile-table']) !!}
    <div class="fields-border">
    <div class="form-group-1 width-50 {!! ($errors && $errors->has('first_name')) ? ' has-error' : '' !!}">
        {!! Form::label('first_name', 'First Name', ['class' => 'control-label']) !!}
        <div>
            {!! Form::text('first_name', old('first_name', $profile->first_name), ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => 'required']) !!}
            {!! Form::errorMessage('first_name') !!}
        </div>
    </div>

    <div class="form-group-1 width-50 {!! ($errors && $errors->has('last_name')) ? ' has-error' : '' !!}">
        {!! Form::label('last_name', 'Last Name', ['class' => 'control-label']) !!}
        <div>
            {!! Form::text('last_name', old('last_name', $profile->last_name), ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => 'required']) !!}
            {!! Form::errorMessage('last_name') !!}
        </div>
    </div>

    <div class="form-group-1 width-50 text-center">
        <div>
            <button type="submit" class="btn-white">Save</button>
        </div>
    </div>
    </div>
    {!! Form::close() !!}

@endsection

