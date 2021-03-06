@extends('app.layouts.twitcher')

@section('content')
    <h1>Review banner</h1>
    <p><a href="/profile/{{ $banner->client_id }}">{{ $banner->client->name }}</a> wants to add banner to your stream with ${{ $banner->amount_limit }} limit</p>
    <p><img src="{{ $banner->file }}"></p>
    <a href="/user/twitcher/banner/accept/{{ $banner->id }}" class="btn-white"><i class="fa fa-check-square-o"></i> Accept this banner</a>
    <a href="/user/twitcher/banner/decline/{{ $banner->id }}" class="btn-white"><i class="fa fa-ban"></i> Decline</a>
@endsection
