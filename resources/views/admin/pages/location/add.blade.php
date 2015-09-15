<?php

$pids = ['0' => 'Select parent'];
foreach ($parents as $r) {
    $pids[$r->id] = $r->title;
}



?>

@extends('admin.layouts.index')

@section('content')

<section class="content-header">
    @if ($location)
        <h1>Add sub <small>for {{ $location->type }} {{ $location->title }}</small></h1>
    @else
        <h1>Add State</h1>
    @endif
    <ol class="breadcrumb">
        <li><a href="/admin/location"><i class="fa fa-dashboard"></i> Locations</a></li>
        <li class="active">Add</li>
    </ol>
</section>

{!! Form::model(new \App\Models\Category(), array('url' => '/admin/location/store/'.$pid)) !!}

    <div class="form-group">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Location name', 'required' => 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('pid', 'Parent', ['class' => 'control-label']) !!}
        <div class="panel panel-default">
            {!! Form::select('pid', $pids, $pid, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('subtitle', 'Description:') !!}
        {!! Form::textarea('subtitle', null, ['class' => 'form-control', 'placeholder' => 'Describe category', 'style' => 'height:100px;']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Add', ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


{!! Form::close() !!}

@endsection