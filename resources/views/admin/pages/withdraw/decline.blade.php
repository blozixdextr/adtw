@extends('admin.layouts.index')

@section('content')
    <h1>Withdrawal decline</h1>
    <p>User: <a href="/admin/withdraw/{{ $withdrawal->id }}/show" class="btn btn-info">Check withdraw</a></p>
    <p>Leave comment why you declined the withdrawal</p>
    {!! Form::open(['url' => '/admin/withdraw/'.$withdrawal->id.'/decline', 'class' => 'form-horizontal']) !!}

    <div class="form-group {!! ($errors && $errors->has('comment')) ? ' has-error' : '' !!}">
        {!! Form::label('comment', 'Comment', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::textarea('comment', old('comment'), ['class' => 'form-control', 'placeholder' => 'Comment', 'required' => 'required']) !!}
            {!! Form::errorMessage('comment') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-lg btn-success">Decline</button>
        </div>
    </div>


    {!! Form::close() !!}

@endsection