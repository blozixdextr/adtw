<?php

$pids = ['0' => 'Select parent'];
foreach ($parents as $r) {
    $pids[$r->id] = $r->title;
}


?>

@extends('admin.layouts.index')

@section('content')

<section class="content-header">
    <h1>
        Edit Location
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/location"><i class="fa fa-dashboard"></i> Locations</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

{!! Form::model($location, array('url' => '/admin/location/update/'.$location->id)) !!}

    <div class="form-group">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Location name', 'required' => 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('pid', 'Parent', ['class' => 'control-label']) !!}
        <div class="panel panel-default">
            {!! Form::select('pid', $pids, null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('subtitle', 'Description:') !!}
        {!! Form::textarea('subtitle', null, ['class' => 'form-control', 'placeholder' => 'Describe location', 'style' => 'height:100px;']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


{!! Form::close() !!}

@endsection