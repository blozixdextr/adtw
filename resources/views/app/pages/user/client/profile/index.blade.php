@extends('app.layouts.client')

@section('content')
    <h1><i class="fa fa-user"></i> Profile</h1>
    {!! Form::open(['url' => '/user/client/profile/save', 'class' => 'form-horizontal profile-table']) !!}

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

    {!! Form::close() !!}

@endsection

