@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            User
        </h1>
    </section>

    <div class="panel panel-default">
        <div class="panel-heading">User's Info</div>
        <div class="panel-body">
            {!! Form::open(['url' => '/admin/user/'.$user->id.'/update']) !!}
            <div class="fields-border">
                <div class="form-group {!! ($errors && $errors->has('email')) ? ' has-error' : '' !!}">
                    {!! Form::label('email', 'Email Address') !!}
                    <div>
                        {!! Form::text('email', old('email', $user->email), ['class' => 'form-control', 'placeholder' => 'Email Address', 'required' => 'required']) !!}
                        {!! Form::errorMessage('email') !!}
                    </div>
                </div>

                <div class="form-group {!! ($errors && $errors->has('name')) ? ' has-error' : '' !!}">
                    {!! Form::label('name', 'Name') !!}
                    <div>
                        {!! Form::text('name', old('name', $user->name), ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required']) !!}
                        {!! Form::errorMessage('name') !!}
                    </div>
                </div>

                <div class="form-group {!! ($errors && $errors->has('oauth_id')) ? ' has-error' : '' !!}">
                    {!! Form::label('oauth_id', 'Oauth id') !!}
                    <div>
                        {!! Form::text('oauth_id', old('oauth_id', $user->oauth_id), ['class' => 'form-control', 'placeholder' => 'oauth_id', 'required' => 'required']) !!}
                        {!! Form::errorMessage('oauth_id') !!}
                    </div>
                </div>

                <div class="form-group {!! ($errors && $errors->has('nickname')) ? ' has-error' : '' !!}">
                    {!! Form::label('nickname', 'Nickname') !!}
                    <div>
                        {!! Form::text('nickname', old('nickname', $user->nickname), ['class' => 'form-control', 'placeholder' => 'Nickname', 'required' => 'required']) !!}
                        {!! Form::errorMessage('nickname') !!}
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-lg btn-success">Save</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="clearfix"></div>



    @endsection