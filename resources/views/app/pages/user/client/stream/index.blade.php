@extends('app.layouts.client')

@section('head-js')
    <script>
    $(function () {
        $('[data-toggle="popover"]').popover({html: true, trigger: 'hover'});
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
    <div class="booking-table booking-table-first panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-calendar"></i> My Orders</h2>
        </div>
        <div class="panel-body">
            <div class="booking-table-row booking-name">
                <div class="col-xs-3">
                    <p>Banner</p>
                </div>
                <div class="col-xs-4">
                    <p>Streamer</p>
                </div>
                <div class="col-xs-3">
                    <p>Cost limit</p>
                </div>
                <div class="col-xs-2">
                    <p>Actions</p>
                </div>
            </div>
        @forelse($orders as $b)
            <div class="booking-table-row booking-name">
                <div class="booking-ban-size">
                    <p>{{ $b->type->title }}</p>
                    <div class="col-xs-3"><i class="fa fa-eye" data-toggle="popover" data-content="<img src='{{ $b->file }}'>"></i></div>
                </div>
                <div class="col-xs-4">
                    <p><a href="/profile/{{ $b->twitcher_id }}">{{ $b->twitcher->name }}</a></p>
                </div>
                <div class="col-xs-3">
                    <p>{{ $b->amount_limit }}USD</p>
                </div>
                <div class="col-xs-2">
                    <p><a href="/user/client/banner/{{ $b->id }}/cancel">Cancel order <i class="fa fa-times"></i></a></p>
                </div>
            </div>
        @empty
            <em>no actual banners</em>
        @endforelse
        </div>
    </div>

    <div class="panel panel-default">
    <h2 class="panel-heading"><i class="fa fa-twitch"></i> Streams</h2>
    <table class="table panel-body booking-table">
            <tr>
                <th class="col-xs-4">Stream Date</th>
                <th class="col-xs-5">Streamer</th>
                <th class="col-xs-3 text-center">Status</th>
            </tr>
        @forelse($streams as $s)
            <tr>
                <td class="col-xs-4"><a href="/user/client/stream/{{ $s->id }}">{{ $s->time_start->format('d.m.Y') }}</a></td>
                <td class="col-xs-5"><a href="/profile/{{ $s->user_id }}">{{ $s->user->name }}</a></td>
                <td class="col-xs-3 text-center">
                    @if ($s->time_end == null)
                        active
                        @else
                        finished
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="3"><em>no streams yet</em></td></tr>
        @endforelse
    </table>
    </div>

    <div class="panel panel-default">
    <h2 class="panel-heading"><i class="fa fa-minus-circle"></i> Inactive banners</h2>
    <table class="table panel-body booking-table">
            <tr>
                <th class="col-xs-3">Banner</th>
                <th class="col-xs-2">Streamer</th>
                <th class="col-xs-2">Status</th>
                <th class="col-xs-2">Cost limit</th>
                <th class="col-xs-3 text-center">Actions</th>
            </tr>
            @forelse($inactiveBanners as $b)
                <tr>
                    <td class="col-xs-3">
                        {{ $b->type->title }} <i class="fa fa-eye" data-toggle="popover" data-content="<img src='{{ $b->file }}'>"></i>
                    </td>
                    <td class="col-xs-2"><a href="/profile/{{ $b->twitcher_id }}">{{ $b->twitcher->name }}</a></td>
                    <td class="col-xs-2">{{ $b->status }}</td>
                    <td class="col-xs-2">{{ $b->amount_limit }}USD</td>
                    <td class="col-xs-3 text-center">
                        @if ($b->status == 'waiting')
                            <a href="/user/client/banner/{{ $b->id }}/cancel">Cancel order <i class="fa fa-times"></i></a>
                        @endif
    
                        @if ($b->status == 'finished')
                            <a href="/user/client/banner/{{ $b->id }}/repeat">Repeat order <i class="fa fa-repeat"></i></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><em>no inactive banners</em></td></tr>
            @endforelse
    </table>
    </div>

@endsection


