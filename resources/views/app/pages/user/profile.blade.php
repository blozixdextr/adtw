@extends('app.layouts.'.$layout)

@section('head-style')
    @if ($layout == 'index')
        <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    @endif
    <link rel="stylesheet" href="/assets/app/css/libs/flags/css/flag-icon.min.css">
@endsection
@section('content')
    <div class="streamer-page">
        <h1>User's Profile</h1>
        @if ($userView->language_id)
            <p>Language: {{ $userView->language->title }}</p>
        @endif
        <h2>{{ $userView->name }}  <small>/ {{ $userView->type }}</small></h2>
        <div class="streamer-page-reg">
            <p>Registered: {{ $userView->created_at->format('Y-m-d') }}</p>
            <p>Last visit: {{ $userView->last_activity->format('Y-m-d') }}</p>
        </div>
        <div class="">
        @if ($userView->type == 'twitcher')
            <p>{{ $userView->twitch_followers }} followers</p>
            <p>{{ $userView->twitch_videos }} videos</p>
            <p>{{ $userView->twitch_views }} views</p>
        @endif
        </div>
        <a href="/user/client/banner/{{ $userView->id }}" class="work-button">Order banner</a>
    </div>
@endsection
