<?php

    $languagesClean = ['' => 'Select your language'];
    foreach ($languages as $l) {
        $languagesClean[$l->id] = $l->title;
    }

    $userBanners = $user->bannerTypes;
    $userBannersId = [];
    foreach ($userBanners as $b) {
        $userBannersId[] = $b->id;
    }

    $userGames = $user->games;
    $userGamesId = [];
    foreach ($userGames as $g) {
        $userGamesId[] = $g->id;
    }

?>

@extends('app.layouts.client')

@section('content')
    <h1>Profile</h1>
    {!! Form::open(['url' => '/user/twitcher/profile/save', 'class' => 'form-horizontal']) !!}

    <div class="form-group {!! ($errors && $errors->has('language')) ? ' has-error' : '' !!}">
        {!! Form::label('language', 'I stream in language', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::select('language', $languagesClean, old('language', $user->language_id), ['class' => 'form-control', 'required' => 'required']) !!}
            {!! Form::errorMessage('language') !!}
        </div>
    </div>

    <div class="form-group {!! ($errors && $errors->has('banner_types')) ? ' has-error' : '' !!}">
        {!! Form::label('banner_types', 'Choose banner sizes you accept', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::errorMessage('banner_types') !!}
            @foreach($bannerTypes as $b)
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('banner_types[]', $b->id, in_array($b->id, $userBannersId)) !!}
                        {{ $b->title }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group {!! ($errors && $errors->has('games')) ? ' has-error' : '' !!}">
        {!! Form::label('games', 'Choose games you stream', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::errorMessage('games') !!}
            @foreach($games as $g)
                @if (count($g->children) > 0)
                    <div style="margin: 10px 0 5px 0">
                        <strong>{{ $g->title }}</strong>
                        <div style="margin: 0 0 0 25px">
                            @foreach($g->children as $gc)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('games[]', $gc->id, in_array($gc->id, $userGamesId)) !!}
                                        {{ $gc->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('games[]', $g->id, in_array($g->id, $userGamesId)) !!}
                            {{ $g->title }}
                        </label>
                    </div>
                @endif

            @endforeach
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-default">Save</button>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

