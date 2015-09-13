@extends('app.layouts.client')

@section('content')
    <h1>Stream #{{ $stream->id }}</h1>

    <p>Twitcher: <a href="/profile/{{ $stream->user_id }}">{{ $stream->user->name }}</a></p>
    <p>Date: {{ $stream->time_start->format('d.m.y H:i') }}</p>
    <p>Status:
        @if ($stream->time_end == null)
            <br>
            <iframe src="{{ $stream->user->twitch_channel->url }}/embed" frameborder="0" scrolling="no" height="378" width="640"></iframe>
            <a href="{{ $stream->user->twitch_channel->url }}?tt_medium=live_embed&tt_content=text_link" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px;text-decoration:underline;">Watch live video from CauthonTV on www.twitch.tv</a>
        @else
            finished
        @endif
    </p>
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
                        @if ($b->getOriginal('pivot_status') == 'waiting')
                        <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/accept" class="btn btn-success">accept</a>
                        <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/decline" class="btn btn-danger">decline</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><em>no your banners here</em></td></tr>
            @endforelse
        </tbody>
    </table>

@endsection

