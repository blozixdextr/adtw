@extends('app.layouts.client')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-timeline.css">
@endsection

@section('content')
    <h1>Client Dashboard</h1>

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