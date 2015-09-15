<?php

$pids = ['0' => 'Select parent'];
foreach ($roots as $r) {
    $pids[$r->id] = $r->title;
}


?>

@extends('admin.layouts.index')

@section('content')

<section class="content-header">
    <h1>
        Edit Category
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/category"><i class="fa fa-dashboard"></i> Categories</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

{!! Form::model($category, array('url' => '/admin/category/update/'.$category->id, 'files' => true)) !!}

    <div class="form-group">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Category name', 'required' => 'required', 'readonly' => 'readonly']) !!}
        <div class="alert alert-info" role="alert">use language file for renaming, url and seo</div>
    </div>

    <div class="form-group">
        {!! Form::label('pid', 'Parent', ['class' => 'control-label']) !!}
        <div class="panel panel-default">
            {!! Form::select('pid', $pids, null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('subtitle', 'Description:') !!}
        {!! Form::textarea('subtitle', null, ['class' => 'form-control', 'placeholder' => 'Describe category', 'style' => 'height:100px;']) !!}
    </div>

    @if ($category->icon)
        <div class="form-group">
            <img src="{{ \App\Models\Mappers\CategoryMapper::getIconSrc($category) }}">
        </div>
    @endif
    <div class="form-group">
        {!! Form::label('icon', 'Icon:') !!}
        {!! Form::file('icon',['class' => 'form-control', 'placeholder' => 'Icon for category', 'accept' => 'image/*']) !!}
    </div>

    @if ($category->image)
        <div class="form-group">
            <img src="{{ \App\Models\Mappers\CategoryMapper::getImageSrc($category) }}">
        </div>
    @endif
    <div class="form-group">
        {!! Form::label('image', 'Image:') !!}
        {!! Form::file('image', ['class' => 'form-control', 'placeholder' => 'Image for category', 'accept' => 'image/*']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


{!! Form::close() !!}

@endsection