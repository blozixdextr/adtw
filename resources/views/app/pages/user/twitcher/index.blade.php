@extends('app.layouts.twitcher')

@section('head-style')
    <!--<link rel="stylesheet" href="/assets/app/css/views/for-banner.css">-->
@endsection

@section('content')
    <h1>Dashboard</h1>
    @foreach($bannerTypes as $bt)
        <div class="row">
            <div class="col-xs-1">{{ $bt->title }}</div>
            @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                <div class="col-xs-2"><a class="btn btn-default" href="/user/twitcher/banner/show/{{ $bt->id }}">start show with {{ count($banners[$bt->id]) }} banners</a></div>
            @else
                <div class="col-xs-2"><em>no orders yet :(</em></div>
            @endif
        </div>
    @endforeach

@endsection