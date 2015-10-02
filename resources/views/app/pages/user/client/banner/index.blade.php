@extends('app.layouts.client')

<?php
    $bannerTypes = [];
    foreach ($userView->bannerTypes as $b) {
        $bannerTypes[$b->id] = $b->title;
    }
?>

@section('content')
    <h1>Banner</h1>

    @if ($balanceEmpty)
        <div class="alert alert-danger" role="alert">You should <a href="/user/client/billing">refill your balance</a> first</div>
    @endif

    {!! Form::open(['url' => '/user/client/banner/save', 'class' => 'form-horizontal', 'files' => true]) !!}

    {!! Form::hidden('user_id', $userView->id) !!}
    <div class="fields-border">
    <div class="form-group {!! ($errors && $errors->has('limit')) ? ' has-error' : '' !!}">
        {!! Form::label('limit', 'Limit', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-2">
            {!! Form::input('number', 'limit', old('limit', floor($user->availableBalance())), ['class' => 'form-control', 'placeholder' => 'Limit payments for this banner', 'required' => 'required', 'style' => 'width: 120px', 'min' => 1, 'max' => floor($user->availableBalance())]) !!}
            {!! Form::errorMessage('limit') !!}
        </div>
        <div class="col-sm-1">USD</div>
    </div>

    <div class="form-group {!! ($errors && $errors->has('banner_type')) ? ' has-error' : '' !!}">
        {!! Form::label('banner_type', 'Banner size', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::select('banner_type', $bannerTypes, old('banner_type', $bannerTypeDefault), ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::errorMessage('banner_type') !!}
        </div>
    </div>

    <div class="form-group {!! ($errors && $errors->has('banner')) ? ' has-error' : '' !!}">
        {!! Form::label('banner', 'Banner  file', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::file('banner', ['class' => 'form-control', 'accept' => 'image/*', 'required' => 'required']) !!}
            {!! Form::errorMessage('banner') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn-white">Order</button>
        </div>
    </div>
    </div>

    {!! Form::close() !!}

@endsection

