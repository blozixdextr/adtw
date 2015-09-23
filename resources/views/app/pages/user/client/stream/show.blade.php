@extends('app.layouts.client')

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
    <div class="in-inline">
        <p>Twitcher: <a href="/profile/{{ $stream->user_id }}">{{ $stream->user->name }}</a></p>
        <p>Date: {{ $stream->time_start->format('d.m.y H:i') }}</p>
        <p>Status:
            @if (!$isFinished)
                <br>
                <iframe src="{{ $stream->user->twitch_channel->url }}/embed" frameborder="0" scrolling="no" height="378" width="640"></iframe>
                <a href="{{ $stream->user->twitch_channel->url }}?tt_medium=live_embed&tt_content=text_link" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px;text-decoration:underline;">Watch live video from CauthonTV on www.twitch.tv</a>
            @else
                finished
            @endif
        </p>
    </div>
    <div class="panel panel-default">
        <h2 class="panel-heading">Your banners in stream</h2>
        <table class="table  booking-table  panel-body">
                <tr>
                    <th class="col-xs-3">Banner size</th>
                    <th class="col-xs-2">Minutes</th>
                    <th class="col-xs-2">Costs</th>
                    <th class="col-xs-2">Status</th>
                    <th class="col-xs-3 text-center">Actions</th>
                </tr>
                @forelse($stream->clientsBanners($user)->get() as $b)
                    <tr>
                        <td class="col-xs-3"><a href="{{ $b->file }}">{{ $b->type->title }}</a></td>
                        <td class="col-xs-2">{{ $b->getOriginal('pivot_minutes') }}</td>
                        <td class="col-xs-2">${{ number_format($b->getOriginal('pivot_amount'), 2) }}</td>
                        <td class="col-xs-2">{{ $b->getOriginal('pivot_status') }}</td>
                        <td class="col-xs-3 text-center">
                            @if ($b->getOriginal('pivot_status') == 'waiting' && $isFinished)
                                <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/accept" class="btn-white little"> accept</a>
                                <a href="/user/client/stream/{{ $stream->id }}/{{ $b->id }}/decline" class="btn-white little"> decline</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><em>no your banners here</em></td></tr>
                @endforelse
        </table>
    </div>
    <div class="panel panel-default">
        <h2 class="panel-heading">Timelogs</h2>
        <table class="table booking-table panel-body">
            <tr class="success-1">
                <td class="col-xs-4">Time</td>
                <td class="col-xs-5">Viewers</td>
                <td class="col-xs-3">Screenshot</td>
            </tr>
            <tbody>
                @forelse($stream->timelogs as $t)
                    @if ($t->status == 'live')
                        <tr class="success-1">
                    @else
                        <tr class="danger-1">
                    @endif
                        <td class="col-xs-4">{{ $t->timeslot_start->format('H:i') }} - {{ $t->timeslot_end->format('H:i') }}</td>
                        <td class="col-xs-5">{{ $t->viewers }}</td>
                        <td class="col-xs-3" class="success-live">
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

