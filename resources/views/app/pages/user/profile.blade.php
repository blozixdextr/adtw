@extends('app.layouts.'.$layout)

@section('head-style')
    @if ($layout == 'index')
        <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    @endif
    <link rel="stylesheet" href="/assets/app/css/libs/flags/css/flag-icon.min.css">
@endsection
@section('content')
    <h1>{{ $userView->name }}  <small>/ {{ $userView->type }}</small></h1>
    <h2>User's Profile</h2>
    <p>Registered: {{ $userView->created_at->format('Y-m-d') }}</p>
    <p>Last visit: {{ $userView->last_activity->format('Y-m-d') }}</p>
    @if ($userView->language_id)
        <p>Language: {{ $userView->language->title }}</p>
    @endif
    @if ($userView->type == 'twitcher')
        <p>{{ $userView->twitch_followers }} followers</p>
        <p>{{ $userView->twitch_videos }} videos</p>
        <p>{{ $userView->twitch_views }} views</p>
        <a href="/user/client/banner/{{ $userView->id }}" class="work-button">Order banner</a>
    @endif
@endsection