@extends('admin.layouts.index')

@section('content')
    <h1>Stream #{{ $stream->id }}</h1>

    <p>Twitcher: <a href="/admin/user/{{ $stream->user_id }}">{{ $stream->user->name }}</a></p>
    <p>Client: <a href="/admin/user/{{ $bannerStream->banner->client_id }}">{{ $bannerStream->banner->client->name }}</a></p>
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
    <img src="{{ $bannerStream->banner->file }}">
    <table class="table">
        <thead>
        <tr>
            <th>Banner size</th>
            <th>Minutes</th>
            <th>Costs</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $bannerStream->banner->type->title }}</td>
                <td>{{ $bannerStream->minutes }}</td>
                <td>{{ $bannerStream->amount }}USD</td>
            </tr>
        </tbody>
    </table>

    <h2>Timelogs</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Time</th>
            <th>Viewers</th>
            <th>Screenshot</th>
        </tr>
        </thead>
        <tbody>
        @forelse($stream->timelogs as $t)
            @if ($t->status == 'live')
                <tr class="success">
            @else
                <tr class="danger">
                    @endif
                    <td>{{ $t->timeslot_start->format('H:i') }} - {{ $t->timeslot_end->format('H:i') }}</td>
                    <td>{{ $t->viewers }}</td>
                    <td>
                        @if ($t->status == 'live' && $t->screenshot)
                            <a href="{{ $t->screenshot }}">{{ $t->status }}</a>
                        @else
                            <em>{{ $t->status }}</em>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr><td colspan="3"><em>no timelogs yet</em></td></tr>
                @endforelse
        </tbody>
    </table>

@endsection