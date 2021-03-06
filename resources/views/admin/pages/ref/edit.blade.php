@extends('admin.layouts.index')

@section('content')
    <h1>Edit  <small>ref of {{ $ref->type }}</small></h1>

    {!! Form::open(['url' => '/admin/ref/'.$ref->id.'/update', 'class' => 'form-horizontal']) !!}

    @if ($parents)
        <div class="form-group {!! ($errors && $errors->has('pid')) ? ' has-error' : '' !!}">
            {!! Form::label('pid', 'Parent', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
                {!! Form::select('pid', $parents->pluck('title', 'id'), old('pid', $ref->pid), ['class' => 'form-control', 'required' => 'required']) !!}
                {!! Form::errorMessage('pid') !!}
            </div>
        </div>
    @endif

    <div class="form-group {!! ($errors && $errors->has('title')) ? ' has-error' : '' !!}">
        {!! Form::label('title', 'Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::text('title', old('title', $ref->title), ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required']) !!}
            {!! Form::errorMessage('title') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-lg btn-success">Save</button>
        </div>
    </div>


    {!! Form::close() !!}

@endsection