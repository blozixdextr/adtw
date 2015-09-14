@extends('app.layouts.twitcher')

@section('content')
    <h1>Stream #{{ $stream->id }}</h1>

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
    <p>Banners in stream</p>
    <table class="table">
        <thead>
            <tr>
                <th>Banner size</th>
                <th>Minutes</th>
                <th>Costs</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stream->banners as $b)
                <tr>
                    <td><a href="{{ $b->file }}">{{ $b->type->title }}</a></td>
                    <td>{{ $b->getOriginal('pivot_minutes') }}</td>
                    <td>${{ $b->getOriginal('pivot_amount') }}</td>
                    <td>{{ $b->getOriginal('pivot_status') }}</td>
                    <td>
                        @if ($b->getOriginal('pivot_status') == 'declining')
                            <a href="/user/twitcher/stream/{{ $stream->id }}/{{ $b->id }}/accept-decline" class="btn btn-success">accept decline</a>
                            <a href="/user/twitcher/stream/{{ $stream->id }}/{{ $b->id }}/complain-decline" class="btn btn-danger">complain decline</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><em>no banners here</em></td></tr>
            @endforelse
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
