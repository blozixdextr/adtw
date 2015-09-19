@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-timeline.css">
@endsection

@section('content')
    <h1>Dashboard</h1>
    <h2>Banners ready to start</h2>
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

    <h2>Timeline</h2>
    <div class="timeline">
        @forelse($notifications as $n)
            <div class="timeline-item">
                <div class="timeline-item-content">
                    <div class="timeline-content-in">
                        <i class="fa fa-twitch"></i>
                        <p>{!! $n->title !!}</p>
                    </div>
                </div>
                <div class="timeline-item-date">
                    <div class="timeline-item-date-in">
                        <hgroup>
                            <h3>{{ $n->created_at->format('l') }},</h3>
                            <h5>the {!! $n->created_at->format('j<\s\u\p>S</\s\u\p> \o\f F') !!}</h5>
                            <h3>{{ $n->created_at->format('H:i') }}</h3>
                        </hgroup>
                    </div>
                </div>
            </div>
        @empty
            <em>no timeline</em>
        @endforelse
    </div>

@endsection