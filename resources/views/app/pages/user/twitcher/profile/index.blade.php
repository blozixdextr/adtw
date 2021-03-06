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

@extends('app.layouts.twitcher')

@section('content')
    <div class="panel panel-default fields-border">
        <div class="panel-heading">
            <h2><i class="fa fa-user"></i> Profile filter</h2>
        </div>
        <div class="panel-body">
    {!! Form::open(['url' => '/user/twitcher/profile/save', 'class' => 'form-inline form-horizontal']) !!}
    <div class="new-flex">
        <div class="flex-left-side">
    <div class="form-group-1 {!! ($errors && $errors->has('language')) ? ' has-error' : '' !!}">
        {!! Form::label('language', 'I stream in language', ['class' => 'control-label']) !!}
        <div>
            {!! Form::select('language', $languagesClean, old('language', $user->language_id), ['class' => 'form-control width-50', 'required' => 'required']) !!}
            {!! Form::errorMessage('language') !!}
        </div>
    </div>

    <div class="form-group-1 form-group-2 {!! ($errors && $errors->has('banner_types')) ? ' has-error' : '' !!}">
        {!! Form::label('banner_types', 'Choose banner sizes you accept', ['class' => 'control-label']) !!}
        <div>
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
    </div>
    <div class="flex-right-side">
    <div class="form-group-1 {!! ($errors && $errors->has('games')) ? ' has-error' : '' !!}">
        {!! Form::label('games', 'Choose games you stream', ['class' => 'control-label']) !!}
        <div class="games">
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
    </div>
    </div>
    <div class="form-group-1 text-center">
        <div>
            <button type="submit" class="btn-white need-mt-15">Save</button>
        </div>
    </div>

    {!! Form::close() !!}
    </div>
</div>
@endsection

