<?php

$pids = ['0' => 'Select parent'];
foreach ($roots as $r) {
    $pids[$r->id] = $r->title;
}



?>

@extends('admin.layouts.index')

@section('content')

<section class="content-header">
    @if ($category)
        <h1>Add Subcategory <small>for {{ $category->title }}</small></h1>
    @else
        <h1>Add Main Category</h1>
    @endif
    <ol class="breadcrumb">
        <li><a href="/admin/category"><i class="fa fa-dashboard"></i> Categories</a></li>
        <li class="active">Add</li>
    </ol>
</section>

{!! Form::model(new \App\Models\Category(), array('url' => '/admin/category/store/'.$pid, 'files' => true)) !!}

    <div class="form-group">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Category name', 'required' => 'required']) !!}
        <div class="alert alert-info" role="alert">remmember to add category to language file</div>
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
        {!! Form::label('icon', 'Icon:') !!}
        {!! Form::file('icon',['class' => 'form-control', 'placeholder' => 'Icon for category', 'accept' => 'image/*']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('image', 'Image:') !!}
        {!! Form::file('image', ['class' => 'form-control', 'placeholder' => 'Image for category', 'accept' => 'image/*']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Add', ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


{!! Form::close() !!}

@endsection