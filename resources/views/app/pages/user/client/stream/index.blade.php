@extends('app.layouts.client')

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
        <div class="booking-table-row">
            <div class="booking-ban-size">
                <p>300 * 250 </p><div class="booking-online"><i class="fa fa-eye"></i><div class="booking-online-img"><img src="http://s3.postimg.org/7oissgeir/presentation3.gif"></div></div>
            </div>
            <div class="booking-streamer">
                <p>ValdemarPrestigiue2</p>
            </div>
            <div class="booking-cost-limit">
                <p>$1.48</p>
            </div>
            <div class="booking-actions">
                <p>Cancel order <i class="fa fa-times"></i></p>
            </div>
        </div>
        <div class="booking-table-row">
            <div class="booking-ban-size">
                <p>300 * 250 </p><div class="booking-online"><i class="fa fa-eye"></i><div class="booking-online-img"><img src="http://s3.postimg.org/7oissgeir/presentation3.gif"></div></div>
            </div>
            <div class="booking-streamer">
                <p>ValdemarPrestigiue2</p>
            </div>
            <div class="booking-cost-limit">
                <p>$1.48</p>
            </div>
            <div class="booking-actions">
                <p>Cancel order <i class="fa fa-times"></i></p>
            </div>
        </div>
        <div class="booking-table-row">
            <div class="booking-ban-size">
                <p>300 * 250 </p><div class="booking-online"><i class="fa fa-eye"></i><div class="booking-online-img"><img src="http://s3.postimg.org/7oissgeir/presentation3.gif"></div></div>
            </div>
            <div class="booking-streamer">
                <p>ValdemarPrestigiue2</p>
            </div>
            <div class="booking-cost-limit">
                <p>$1.48</p>
            </div>
            <div class="booking-actions">
                <p>Cancel order <i class="fa fa-times"></i></p>
            </div>
        </div>
        <div class="booking-table-row">
            <div class="booking-ban-size">
                <p>300 * 250 </p><div class="booking-online"><i class="fa fa-eye"></i><div class="booking-online-img"><img src="http://s3.postimg.org/7oissgeir/presentation3.gif"></div></div>
            </div>
            <div class="booking-streamer">
                <p>ValdemarPrestigiue2</p>
            </div>
            <div class="booking-cost-limit">
                <p>$1.48</p>
            </div>
            <div class="booking-actions">
                <p>Cancel order <i class="fa fa-times"></i></p>
            </div>
        </div>
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


