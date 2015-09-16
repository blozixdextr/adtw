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
    <h1>Streamers Filter</h1>
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
                                        {!! Form::checkbox('languages[]', $l->id, in_array($l->id, (isset($filters['languages']) && count($filters['languages']) > 0) ? $filters['languages'] : [])) !!}
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
                                        {!! Form::checkbox('banner_types[]', $b->id, in_array($b->id, (isset($filters['banner_types']) && count($filters['banner_types']) > 0) ? $filters['banner_types'] : [])) !!}
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
                <div class="col-md-2">

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
                                {!! Form::label('videos', 'Views', ['class' => 'control-label']) !!}
                                {!! Form::text('videos', old('videos', $filters['videos']), ['class' => 'form-control', 'placeholder' => 'Minimum views']) !!}
                                {!! Form::errorMessage('videos') !!}
                            </div>

                            <div class="form-group" style="margin-top:40px">
                                <button type="submit" class="btn btn-default btn-lg">Find</button>
                            </div>

                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <h2>Search results</h2>

    <div class="streamers-list-new">
       
        @forelse($twitchers as $u)
        <div class="streamer-id">
            <div class="streamer-id-follow">
              <div class="str-id-photo"><img src="https://cloud.githubusercontent.com/assets/14276761/9874571/82ee419c-5baf-11e5-86da-a91d06cbc689.jpeg" alt=""></div>
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
                      <p><i class="fa fa-language"></i> {!! twitcherLanguageToFlag($u) !!}</p>
                  </li>
                  <li>
                      <p><i class="fa fa-gamepad"></i> Dota2, Counter-Strike: GO, WarCraft 3</p>
                  </li>
              </ul>
          </div>
          <div class="streamer-id-button">
              <a href="/user/client/banner/{{ $u->id.$bannerTypesParam }}" class="work-button">Order Banner Now</a>
          </div>

        @empty
            <em>no results</em>
        @endforelse
    	</div>
        
        
        
    </div>
    <div class="clear"></div>
    
    
    <div class="streamers-list-new">
      <div class="streamer-id">
          <div class="streamer-id-follow">
              <div class="str-id-photo"><img src="https://cloud.githubusercontent.com/assets/14276761/9874571/82ee419c-5baf-11e5-86da-a91d06cbc689.jpeg" alt=""></div>
              <div class="str-id-following"><span><i class="fa fa-eye"></i> 65,989,753 views</span></div>
          </div>
          <div class="streamer-id-name">Stanislav Studzinskyy</div>
          <div class="streamer-id-info">
              <ul>
                   <li>
                      <p><i class="fa fa-heart-o"></i> 808,964 Followers</p>
                  </li>
                  <li>
                      <p><i class="fa fa-video-camera"></i> 256 Videos</p>
                  </li>
                  <li>
                      <p><i class="fa fa-language"></i> English Language</p>
                  </li>
                  <li>
                      <p><i class="fa fa-gamepad"></i> Dota2, Counter-Strike: GO, WarCraft 3</p>
                  </li>



              </ul>
          </div>
          <div class="streamer-id-button">
              <a href="">Order now</a>
          </div>
      </div>
      
      
      <div class="streamer-id">
          <div class="streamer-id-follow">
              <div class="str-id-photo"><img src="https://cloud.githubusercontent.com/assets/14276761/9874571/82ee419c-5baf-11e5-86da-a91d06cbc689.jpeg" alt=""></div>
              <div class="str-id-following"><span><i class="fa fa-eye"></i> 65,989,753 views</span></div>
          </div>
          <div class="streamer-id-name">Stanislav Studzinskyy</div>
          <div class="streamer-id-info">
              <ul>
                   <li>
                      <p><i class="fa fa-heart-o"></i> 808,964 Followers</p>
                  </li>
                  <li>
                      <p><i class="fa fa-video-camera"></i> 256 Videos</p>
                  </li>
                  <li>
                      <p><i class="fa fa-language"></i> English Language</p>
                  </li>
                  <li>
                      <p><i class="fa fa-gamepad"></i> Dota2, Counter-Strike: GO, WarCraft 3</p>
                  </li>



              </ul>
          </div>
          <div class="streamer-id-button">
              <a href="">Order now</a>
          </div>
      </div>
   
   
   <div class="streamer-id">
          <div class="streamer-id-follow">
              <div class="str-id-photo"><img src="https://cloud.githubusercontent.com/assets/14276761/9874571/82ee419c-5baf-11e5-86da-a91d06cbc689.jpeg" alt=""></div>
              <div class="str-id-following"><span><i class="fa fa-eye"></i> 65,989,753 views</span></div>
          </div>
          <div class="streamer-id-name">Stanislav Studzinskyy</div>
          <div class="streamer-id-info">
              <ul>
                   <li>
                      <p><i class="fa fa-heart-o"></i> 808,964 Followers</p>
                  </li>
                  <li>
                      <p><i class="fa fa-video-camera"></i> 256 Videos</p>
                  </li>
                  <li>
                      <p><i class="fa fa-language"></i> English Language</p>
                  </li>
                  <li>
                      <p><i class="fa fa-gamepad"></i> Dota2, Counter-Strike: GO, WarCraft 3</p>
                  </li>



              </ul>
          </div>
          <div class="streamer-id-button">
              <a href="">Order now</a>
          </div>
      </div>
   
   <div class="streamer-id">
          <div class="streamer-id-follow">
              <div class="str-id-photo"><img src="https://cloud.githubusercontent.com/assets/14276761/9874571/82ee419c-5baf-11e5-86da-a91d06cbc689.jpeg" alt=""></div>
              <div class="str-id-following"><span><i class="fa fa-eye"></i> 65,989,753 views</span></div>
          </div>
          <div class="streamer-id-name">Stanislav Studzinskyy</div>
          <div class="streamer-id-info">
              <ul>
                   <li>
                      <p><i class="fa fa-heart-o"></i> 808,964 Followers</p>
                  </li>
                  <li>
                      <p><i class="fa fa-video-camera"></i> 256 Videos</p>
                  </li>
                  <li>
                      <p><i class="fa fa-language"></i> English Language</p>
                  </li>
                  <li>
                      <p><i class="fa fa-gamepad"></i> Dota2, Counter-Strike: GO, WarCraft 3</p>
                  </li>



              </ul>
          </div>
          <div class="streamer-id-button">
              <a href="">Order now</a>
          </div>
      </div>
    
     
    </div>

    {!! $twitchers->render() !!}

@endsection

