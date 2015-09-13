@extends('app.layouts.client')

@section('content')
    <h1>Decline</h1>

    <p>Twitcher: <a href="/profile/{{ $stream->user_id }}">{{ $stream->user->name }}</a></p>
    <p>Date: {{ $stream->time_start->format('d.m.y H:i') }}</p>

    <p>Your banners in stream</p>
    <table class="table">
        <thead>
            <tr>
                <th>Banner size</th>
                <th>Views</th>
                <th>Minutes</th>
                <th>Costs</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stream->clientsBanners($user)->get() as $b)
                <tr>
                    <td><a href="{{ $b->file }}">{{ $b->type->title }}</a></td>
                    <td>{{ $b->getOriginal('pivot_viewers') }}</td>
                    <td>{{ $b->getOriginal('pivot_minutes') }}</td>
                    <td>${{ $b->getOriginal('pivot_amount') }}</td>
                    <td>{{ $b->getOriginal('pivot_status') }}</td>
                    <td>
                        <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/accept" class="btn btn-success">accept</a>
                        <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/decline" class="btn btn-danger">decline</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><em>no your banners here</em></td></tr>
            @endforelse
        </tbody>
    </table>

@endsection

