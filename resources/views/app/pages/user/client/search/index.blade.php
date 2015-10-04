<?php

if (isset($filters['banner_types']) && count($filters['banner_types']) > 0) {
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
    
    <div class="panel panel-default choose-strimer">
        <div class="panel-heading"><h2><i class="fa fa-filter"></i> Streamers Filter</h2></div>
        <div class="panel-body">
            {!! Form::open(['url' => '/user/client/search', 'class' => '', 'method' => 'get']) !!}

                <div class="language-and-banner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group {!! ($errors && $errors->has('language')) ? ' has-error' : '' !!}">
                        {!! Form::label('language', 'Languages', ['class' => 'control-label']) !!}
                        <div class="">
                            {!! Form::errorMessage('language') !!}
                            @foreach($languageRefs as $l)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('languages[]', $l->id, in_array($l->id, (isset($filters['languages']) && count($filters['languages']) > 0) ? $filters['languages'] : [])) !!}
                                        {{ $l->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                

                
                    <div class="form-group {!! ($errors && $errors->has('banner_types')) ? ' has-error' : '' !!}">
                        {!! Form::label('banner_types', 'Banner sizes', ['class' => 'control-label']) !!}
                        <div class="">
                            {!! Form::errorMessage('banner_types') !!}
                            @foreach($bannerTypeRefs as $b)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('banner_types[]', $b->id, in_array($b->id, (isset($filters['banner_types']) && count($filters['banner_types']) > 0) ? $filters['banner_types'] : [])) !!}
                                        {{ $b->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="games col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                                        {!! Form::checkbox('games[]', $gc->id, in_array($gc->id, (isset($filters['games']) && count($filters['games']) > 0) ? $filters['games'] : [])) !!}
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
                
                        <div class="games-followers col-lg-3 col-md-3 col-sm-3 col-xs-12  fields-border">

                            <div class="form-group {!! ($errors && $errors->has('name')) ? ' has-error' : '' !!}">
                                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                                {!! Form::text('name', old('name', $filters['name']), ['class' => 'form-control', 'placeholder' => 'Streamer\'s name']) !!}
                                {!! Form::errorMessage('name') !!}
                            </div>

                            <div class="form-group {!! ($errors && $errors->has('followers')) ? ' has-error' : '' !!}">
                                {!! Form::label('followers', 'Followers', ['class' => 'control-label']) !!}
                                {!! Form::text('followers', old('followers', $filters['followers']), ['class' => 'form-control', 'placeholder' => 'Minimum followers']) !!}
                                {!! Form::errorMessage('followers') !!}
                            </div>

                            <div class="form-group {!! ($errors && $errors->has('views')) ? ' has-error' : '' !!}">
                                {!! Form::label('views', 'Views', ['class' => 'control-label']) !!}
                                {!! Form::text('views', old('views', $filters['views']), ['class' => 'form-control', 'placeholder' => 'Minimum views']) !!}
                                {!! Form::errorMessage('views') !!}
                            </div>

                            <div class="form-group {!! ($errors && $errors->has('videos')) ? ' has-error' : '' !!}">
                                {!! Form::label('videos', 'Videos', ['class' => 'control-label']) !!}
                                {!! Form::text('videos', old('videos', $filters['videos']), ['class' => 'form-control', 'placeholder' => 'Minimum videos']) !!}
                                {!! Form::errorMessage('videos') !!}
                            </div>
                        </div>
                    </div>
                        <div class="games-button">
                            <div class="form-group" style="margin-top:40px">
                                <button type="submit" class="btn-white">Find</button>
                            </div>
                        </div>
                
            {!! Form::close() !!}
        </div>
    </div>
    <div class="col-xs-12 help-window">
        <a href="#" onclick="toggle_visibility('foo');">Read help <i class="fa fa-question-circle"></i></a>
        <div class="panel panel-default panel-body" id="foo">This is foo This is foo  This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo</div>
    </div>
    <div class="height30"></div>
    <h2><i class="fa fa-search"></i> Search results</h2>
    
    <div class="streamers-list-new">
        @forelse($twitchers as $u)
            <div class="streamer-id">
                <div class="streamer-id-follow">
                    <div class="str-id-photo"><img src="{{ $u->profile->avatar }}" alt=""></div>
                    <div class="str-id-following"><span><i class="fa fa-eye"></i> {{ $u->twitch_views }} views</span></div>
                </div>
                <div class="streamer-id-name"><a href="/profile/{{ $u->id }}">{{ $u->name }}</a></div>
                <div class="streamer-id-info">
                    <ul>
                        <li>
                            <p><i class="fa fa-heart-o"></i> {{ $u->twitch_followers }} Followers</p>
                        </li>
                        <li>
                            <p><i class="fa fa-video-camera"></i> {{ $u->twitch_videos }} Videos</p>
                        </li>
                        <li>
                            <p><i class="fa fa-language"></i> {{ $u->language_id > 0 ? ucfirst($u->language->title) : 'English' }} Language</p>
                        </li>
                        <li>
                            <p><i class="fa fa-gamepad"></i> {{ $u->games->implode('title', ', ') }}</p>
                        </li>
                    </ul>
                </div>
                <div class="streamer-id-button">
                    <a href="/user/client/banner/{{ $u->id.$bannerTypesParam }}">Order now</a>
                </div>
            </div>
        @empty
            <em>no results</em>
        @endforelse
    </div>

    {!! $twitchers->appends([
            'games' => $filters['games'],
            'banner_types' => $filters['banner_types'],
            'languages' => $filters['languages'],
            'name' => $filters['name'],
            'followers' => $filters['followers'],
            'views' => $filters['views'],
            'videos' => $filters['videos'],
        ])->render() !!}

@endsection

