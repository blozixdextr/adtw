@extends('app.layouts.twitcher')

@section('head-js')
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover({html: true, trigger: 'hover', placement: 'left'});
        })
    </script>
@endsection

@section('head-style')
    <style>
        .popover {
            width: auto;
            max-width: 800px;
        }

        .second-page .page {
            overflow: visible;
        }
    </style>
@endsection

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
    <div class="panel booking-table panel-default">
        <h2 class="panel-heading">Banners in stream</h2>
        <table class="table panel-body">
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
                        <td>${{ number_format($b->getOriginal('pivot_amount'), 2) }}</td>
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
    </div>
    <div class="panel panel-default">
        <h2 class="panel-heading">Timelogs</h2>
        <table class="table booking-table panel-body">
            <tr class="success-1">
                <td>Time</td>
                <td>Viewers</td>
                <td>Screenshot</td>
            </tr>
            <tbody>
                @forelse($stream->timelogs as $t)
                    @if ($t->status == 'live')
                        <tr class="success-1">
                    @else
                        <tr class="danger-1">
                    @endif
                        <td>{{ $t->timeslot_start->format('H:i') }} - {{ $t->timeslot_end->format('H:i') }}</td>
                        <td>{{ $t->viewers }}</td>
                        <td>
                            @if ($t->status == 'live' && $t->screenshot)
                                <a href="{{ $t->screenshot }}">{{ $t->status }}</a>
                                <div class="streamer-online-view"><i class="fa fa-eye" data-toggle="popover" data-content="<img src='{{ $t->screenshot }}'>"></i></div>
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
    </div>
@endsection

