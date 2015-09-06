<?php

if (isset($filters['banner_types'])) {
    foreach ($filters['banner_types'] as $t) {
        $searchForBannerTypes[] = $t;
    }
    $bannerTypesParam = '?b='.implode(',', $searchForBannerTypes);
} else {
    $searchForBannerTypes = [];
    $bannerTypesParam = '';
}



?>
@extends('app.layouts.client')
@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/libs/flags/css/flag-icon.min.css">
    <link rel="stylesheet" href="/assets/app/css/views/for-streamers.css">
@endsection
@section('content')
    <h1>Search twitchers</h1>
    <div class="panel panel-default">
        <div class="panel-heading">Filter</div>
        <div class="panel-body">
            {!! Form::open(['url' => '/user/client/search', 'class' => '', 'method' => 'get']) !!}

                <div class="col-md-3">
                    <div class="form-group {!! ($errors && $errors->has('language')) ? ' has-error' : '' !!}">
                        {!! Form::label('language', 'Languages', ['class' => 'control-label']) !!}
                        <div class="">
                            {!! Form::errorMessage('language') !!}
                            @foreach($languageRefs as $l)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('languages[]', $l->id, in_array($l->id, isset($filters['languages']) ? $filters['languages'] : [])) !!}
                                        {{ $l->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group {!! ($errors && $errors->has('banner_types')) ? ' has-error' : '' !!}">
                        {!! Form::label('banner_types', 'Banner sizes', ['class' => 'control-label']) !!}
                        <div class="">
                            {!! Form::errorMessage('banner_types') !!}
                            @foreach($bannerTypeRefs as $b)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('banner_types[]', $b->id, in_array($b->id, isset($filters['banner_types']) ? $filters['banner_types'] : [])) !!}
                                        {{ $b->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {!! ($errors && $errors->has('games')) ? ' has-error' : '' !!}">
                        {!! Form::label('games', 'Games', ['class' => 'control-label']) !!}
                        <div class="">
                            {!! Form::errorMessage('games') !!}
                            @foreach($gameRefs as $g)
                                @if (count($g->children) > 0)
                                    <div style="margin: 10px 0 5px 0">
                                        <strong>{{ $g->title }}</strong>
                                        <div style="margin: 0 0 0 25px">
                                            @foreach($g->children as $gc)
                                                <div class="checkbox">
                                                    <label>
                                                        {!! Form::checkbox('games[]', $gc->id, in_array($gc->id, isset($filters['games']) ? $filters['games'] : [])) !!}
                                                        {{ $gc->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('games[]', $g->id, in_array($g->id, isset($filters['games']) ? $filters['games'] : [])) !!}
                                            {{ $g->title }}
                                        </label>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-default">Find</button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <h2>Search results</h2>

    <div class="streamers-list">
        <div class="row">
        @forelse($twitchers as $u)
            <div class="col-md-6">
                <div class="streamer-item violet">
                    <div class="streamer-img"><i class="fa fa-twitch"></i></div>
                    <div class="streamer-info">
                        <h5><a href="/profile/{{ $u->id }}">{{ $u->name }}</a></h5>
                        <div>
                            <p>{{ $u->twitch_followers }} followers</p> /
                            <p>{{ $u->twitch_videos }} videos</p> /
                            <p>{{ $u->twitch_views }} views</p>
                            {!! twitcherLanguageToFlag($u) !!}
                        </div>
                    </div>
                    <div class="streamer-buy">
                        <a href="/user/client/banner/{{ $u->id.$bannerTypesParam }}" class="work-button">Order</a>
                    </div>
                </div>
            </div>
        @empty
            <em>no results</em>
        @endforelse
        </div>
        <div class="clear"></div>
    </div>

    {!! $twitchers->render() !!}

@endsection

