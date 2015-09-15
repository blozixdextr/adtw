@extends('app.layouts.client')

@section('content')
    <h1>Profile</h1>
    {!! Form::open(['url' => '/user/client/profile/save', 'class' => 'form-horizontal']) !!}

    <div class="form-group {!! ($errors && $errors->has('first_name')) ? ' has-error' : '' !!}">
        {!! Form::label('first_name', 'First Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::text('first_name', old('first_name', $profile->first_name), ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => 'required']) !!}
            {!! Form::errorMessage('first_name') !!}
        </div>
    </div>

    <div class="form-group {!! ($errors && $errors->has('last_name')) ? ' has-error' : '' !!}">
        {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::text('last_name', old('last_name', $profile->last_name), ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => 'required']) !!}
            {!! Form::errorMessage('last_name') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn-violet">Save</button>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

