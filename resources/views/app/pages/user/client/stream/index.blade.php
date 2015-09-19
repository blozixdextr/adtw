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

    <h1>My Orders</h1>
    <div class="booking-table">
        <div class="booking-table-row booking-name">
            <div class="booking-ban-size">
                <p>Banner</p>
            </div>
            <div class="booking-streamer">
                <p>Streamer</p>
            </div>
            <div class="booking-cost-limit">
                <p>Cost limit</p>
            </div>
            <div class="booking-actions">
                <p>Actions</p>
            </div>
        </div>
        @forelse($orders as $b)
            <div class="booking-table-row booking-name">
                <div class="booking-ban-size">
                    <p>{{ $b->type->title }}</p>
                    <div class="booking-online"><i class="fa fa-eye" data-toggle="popover" data-content="<img src='{{ $b->file }}'>"></i></div>
                </div>
                <div class="booking-streamer">
                    <p><a href="/profile/{{ $b->twitcher_id }}">{{ $b->twitcher->name }}</a></p>
                </div>
                <div class="booking-cost-limit">
                    <p>{{ $b->amount_limit }}USD</p>
                </div>
                <div class="booking-actions">
                    <p><a href="/user/client/banner/{{ $b->id }}/cancel">Cancel order <i class="fa fa-times"></a></i></p>
                </div>
            </div>
        @empty
            <em>no actual banners</em>
        @endforelse
    </div>

    <h1>Streams</h1>
    <table class="table">
        <tr>
            <th>Stream Date</th>
            <th>Twitcher</th>
            <th>Status</th>
        </tr>
    @forelse($streams as $s)
        <tr>
            <td><a href="/user/client/stream/{{ $s->id }}">{{ $s->time_start->format('d.m.Y') }}</a></td>
            <td><a href="/profile/{{ $s->user_id }}">{{ $s->user->name }}</a></td>
            <td>
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
@endsection


